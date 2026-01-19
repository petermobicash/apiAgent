<?php

namespace App\Http\Controllers\API\coreBank414\UsersRegistration;

use Illuminate\Http\Request;
use App\Http\Controllers\API\coreBank414\CoreBank414BaseController as BaseController;
use Validator;
use App\Services\UserEnrollmentService;
use App\Services\Utilities\GuzzleHttpClient;
use App\Http\Requests\IndependentUserEnrollmentRequest;
use App\Http\Requests\DependentUserEnrollmentRequest;
use App\Http\Requests\StudentOnboardingRequest;
use App\Exceptions\MobicoreApiException;

/**
 * @group User Enrollment CoreBank414
 *
 * API endpoints for managing user enrollment operations in the coreBank414 system.
 *
 * This controller handles various enrollment types including independent agents, dependent agents,
 * students, schools, parents, clients, and operators. It implements a layered architecture with:
 * - Form Request validation (IndependentUserEnrollmentRequest, DependentUserEnrollmentRequest, StudentOnboardingRequest)
 * - DTO-based data transfer (IndependentUserDTO, DependentUserDTO, StudentDTO, etc.)
 * - Service layer abstraction (UserEnrollmentService)
 * - HTTP client dependency injection (HttpClientInterface)
 *
 * Key features:
 * - Professional validation using Laravel Form Requests
 * - Type-safe data handling with DTOs and Enums
 * - Standardized API responses with error mapping
 * - Authorization header validation
 * - Exception handling with custom MobicoreApiException
 */

class UserEnrollmentCoreBank414Controller extends BaseController
{
    /**
     * The user enrollment service instance.
     *
     * Injected via dependency injection to handle business logic for enrollment operations.
     * Uses HttpClientInterface for API communication.
     *
     * @var UserEnrollmentService
     */
    protected UserEnrollmentService $userEnrollmentService;

    /**
     * Constructor - Injects the UserEnrollmentService dependency.
     *
     * @param UserEnrollmentService $userEnrollmentService Service for handling enrollment business logic
     */
    public function __construct(UserEnrollmentService $userEnrollmentService)
    {
        $this->userEnrollmentService = $userEnrollmentService;
    }

    /**
     * Checks if the request contains a valid Authorization header.
     *
     * This is a security measure to ensure all enrollment operations are authenticated.
     * Returns an error response if authorization header is missing.
     *
     * @param Request $request The incoming HTTP request
     * @return array|null Error response array if authorization fails, null if valid
     */
    private function checkAuthorization(Request $request)
    {
        // Verify that Authorization header is present in the request
        if (!$request->header('Authorization')) {
            return $this->formatError(104, 'Authorization please');
        }
        return null;
    }

    /**
     * Validates request data against provided validation rules.
     *
     * Uses Laravel's Validator to check input data and returns formatted error
     * response if validation fails. This ensures data integrity before processing.
     *
     * @param array $rules Validation rules array (Laravel validation format)
     * @param Request $request The HTTP request containing data to validate
     * @return array|null Error response array if validation fails, null if passes
     */
    private function validateRequest(array $rules, Request $request)
    {
        // Create validator instance with request data and rules
        $validator = Validator::make($request->all(), $rules);

        // Return formatted error if validation fails
        if ($validator->fails()) {
            return $this->formatError(105, 'Data validation', $validator->errors()->toArray());
        }
        return null;
    }

    /**
     * Formats a successful API response in the standardized format.
     *
     * All successful operations return data in this consistent structure
     * with response code 100, SUCCESS status, and timestamp.
     *
     * @param mixed $data The response data payload
     * @param string $description Optional description of the success operation
     * @return array Standardized success response array
     */
    private function formatSuccess($data, $description = 'SUCCESS')
    {
        return [
            "responseCode" => 100,
            "communicationStatus" => 'SUCCESS',
            "codeDescription" => $description,
            "data" => $data,
            "responseDate" => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Formats an error API response in the standardized format.
     *
     * All error conditions return data in this consistent structure
     * with appropriate error codes, FAILURE status, and optional error details.
     *
     * @param int $code Error code (100-107 range for different error types)
     * @param string $description Human-readable error description
     * @param mixed $data Optional additional error data (validation errors, API response, etc.)
     * @return array Standardized error response array
     */
    private function formatError($code, $description, $data = null)
    {
        return [
            "responseCode" => $code,
            "communicationStatus" => 'FAILURE',
            "codeDescription" => $description,
            "data" => $data,
            "responseDate" => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Processes and formats API responses from the coreBank414 system.
     *
     * Handles successful user creation responses and maps various error conditions
     * to standardized error codes. This centralizes API response handling logic.
     *
     * @param string $response Raw JSON response from the API
     * @return array Formatted response array (success or error)
     */
    private function handleApiResponse($response)
    {
        // Decode the JSON response from the external API
        $decoded = json_decode($response);

        // Handle successful user creation - extract user details
        if (isset($decoded->user)) {
            return $this->formatSuccess([
                "id" => $decoded->user->id,
                "display" => $decoded->user->display,
                "principal" => $decoded->principals ?? null
            ]);
        }

        // Handle API errors by mapping to standardized codes
        if (isset($decoded->code)) {
            $code = $this->mapErrorCode($decoded);
            return $this->formatError($code, $this->mapErrorDescription($decoded), $decoded);
        }

        // Fallback for unexpected response formats
        return $this->formatError(107, 'FAILURE', $decoded);
    }

    /**
     * Maps coreBank414 API error codes to standardized response codes.
     *
     * Translates various API error conditions into consistent numeric codes
     * used throughout the application for error handling.
     *
     * @param object $response Decoded API error response
     * @return int Standardized error code (101-107)
     */
    private function mapErrorCode($response)
    {
        // Handle authentication-related errors
        if ($response->code == 'login') {
            if (isset($response->passwordStatus)) return 102; // Password blocked
            if (isset($response->userStatus)) return 101; // User status issues
            return 103; // General login failure
        }

        // Handle business logic errors
        if ($response->code == 'insufficientBalance') return 106; // Balance issues
        if (isset($response->customFieldErrors)) return 105; // Validation errors

        // Default failure code
        return 107;
    }

    /**
     * Maps coreBank414 API error codes to human-readable descriptions.
     *
     * Provides user-friendly error messages for different API error conditions.
     *
     * @param object $response Decoded API error response
     * @return string Human-readable error description
     */
    private function mapErrorDescription($response)
    {
        // Authentication error descriptions
        if ($response->code == 'login') {
            if (isset($response->passwordStatus)) return 'Password is temporarily blocked';
            if (isset($response->userStatus)) return 'User is ' . $response->userStatus;
            return 'Invalid authentication';
        }

        // Business logic error descriptions
        if ($response->code == 'insufficientBalance') return 'Insufficient Balance';
        if (isset($response->customFieldErrors)) return 'Custom Field Errors';

        // Default error description
        return 'FAILURE';
    }

    /**
     * Enrolls an independent user (mini agent) in the coreBank414 system.
     *
     * This endpoint handles the registration of independent business agents with full
     * business details, identity verification, and address information. Uses the
     * IndependentUserEnrollmentRequest for validation and IndependentUserDTO for data transfer.
     *
     * @param IndependentUserEnrollmentRequest $request Validated request containing user data
     * @return array Standardized API response with user creation result
     * @throws MobicoreApiException When enrollment operation fails
     */
    public function userIndependantEnrollment(IndependentUserEnrollmentRequest $request)
    {
        try {
            // Check for required authorization header to ensure authenticated access
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Convert validated request data to DTO for type safety and clean data transfer
            $dto = $request->toDTO();

            // Extract authorization header for API authentication with coreBank414
            $header = $request->header('Authorization');

            // Call service layer to handle enrollment business logic via UserEnrollmentService
            $response = $this->userEnrollmentService->enrollIndependentUser($dto, $header);

            // Format and return standardized API response
            return $this->handleApiResponse($response);
        } catch (\Exception $e) {
            // Throw custom exception with context for error handling and logging
            throw new MobicoreApiException('Enrollment failed: ' . $e->getMessage());
        }
    }

    /**
     * Enrolls a dependent user (retail agent) in the coreBank414 system.
     *
     * This endpoint handles the registration of dependent agents who work under
     * a main broker. Includes broker association and business details. Uses the
     * DependentUserEnrollmentRequest for validation and DependentUserDTO for data transfer.
     *
     * @param DependentUserEnrollmentRequest $request Validated request containing user data
     * @return array Standardized API response with user creation result
     * @throws MobicoreApiException When enrollment operation fails
     */
    public function userDependantEnrollment(DependentUserEnrollmentRequest $request)
    {
        try {
            // Check for required authorization header to ensure authenticated access
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Convert validated request data to DTO for type safety and clean data transfer
            $dto = $request->toDTO();

            // Extract authorization header for API authentication with coreBank414
            $header = $request->header('Authorization');

            // Call service layer to handle enrollment business logic via UserEnrollmentService
            $response = $this->userEnrollmentService->enrollDependentUser($dto, $header);

            // Decode response to check for successful user creation and handle broker association
            $decoded = json_decode($response);

            if (isset($decoded->user)) {
                // TODO: Implement settingAgentMainBroker in UserEnrollmentService
                // This would associate the dependent agent with their main broker post-enrollment
                return $this->formatSuccess([
                    "id" => $decoded->user->id,
                    "display" => $decoded->user->display,
                    "principal" => $decoded->principals ?? null
                ]);
            }

            // Handle other response types (errors, etc.) through standard API response handler
            return $this->handleApiResponse($response);
        } catch (\Exception $e) {
            // Throw custom exception with context for error handling and logging
            throw new MobicoreApiException('Enrollment failed: ' . $e->getMessage());
        }
    }

    /**
     * Enrolls an operator user under a main agent in the coreBank414 system.
     *
     * This endpoint creates operator accounts that are associated with main agents.
     * Operators have limited permissions and work under the supervision of main agents.
     * Includes identity verification, position details, and PIN setup.
     *
     * Note: This method uses the legacy UserEnrollment class and should be refactored
     * to use OperatorDTO and Form Request validation for consistency.
     *
     * @param Request $request The HTTP request containing operator enrollment data
     * @return array Standardized API response with operator creation result
     * @throws MobicoreApiException When enrollment operation fails
     */
    public function userOperatorEnrollment(Request $request)
    {
        try {
            // Validate authorization header presence for security
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Define validation rules for operator enrollment data
            $rules = [
                "operatorName" => 'required|string',
                "operatorUserName" => 'required|string',
                "operatorUserEmail" => 'required|email',
                "nationality" => 'required',
                "country_code" => 'required|string',
                "identity_type" => 'required|string',
                "identity_number" => 'required',
                "registration_date" => 'required',
                "email_validation_date" => 'required',
                "position" => 'required|string',
                "mainUserName" => 'required|string',
                "mainPhone" => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                "pin" => 'required|string',
                "confirmationPin" => 'required|string'
            ];

            // Validate request data against rules
            if ($error = $this->validateRequest($rules, $request)) {
                return $error;
            }

            // Extract authorization header for API calls
            $header = $request->header('Authorization');

            // Call legacy enrollment class to create operator
            // TODO: Refactor to use UserEnrollmentService and OperatorDTO
            $response = $this->userEnrollmentService->userOperatorEnrollment(
                $request->operatorName,
                $request->operatorUserName,
                $request->operatorUserEmail,
                $request->nationality,
                $request->country_code,
                $request->identity_type,
                $request->identity_number,
                $request->registration_date,
                $request->email_validation_date,
                $request->position,
                $request->mainUserName,
                $request->mainPhone,
                $request->pin,
                $request->confirmationPin,
                $header
            );

            // Process and return standardized API response
            return $this->handleApiResponse($response);
        } catch (\Exception $e) {
            // Handle and re-throw exceptions with context
            throw new MobicoreApiException('Enrollment failed: ' . $e->getMessage());
        }
    }

    /**
     * Retrieves operator groups available to the authenticated user.
     *
     * This endpoint returns the list of operator groups that the current user
     * has access to manage. Used for displaying available groups in admin interfaces.
     *
     * Note: Method name suggests broker setting but actually retrieves groups.
     * Should be renamed for clarity.
     *
     * @param Request $request The HTTP request (authorization required)
     * @return array Standardized API response with operator groups data
     * @throws MobicoreApiException When retrieval operation fails
     */
    public function groupsOperatorView(Request $request)
    {
        try {
            // Ensure user is authenticated
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Get authorization header for API call
            $header = $request->header('Authorization');

            // Call legacy method to retrieve operator groups
            $response = $this->userEnrollmentService->viewOperatorGroups($header);

            // Decode API response
            $decoded = json_decode($response);

            // Return success with groups data or error if no data
            if (isset($decoded)) {
                return $this->formatSuccess($decoded, "Operator groups retrieved successfully");
            } else {
                return $this->formatError(400, "Failed to retrieve operator groups");
            }
        } catch (\Exception $e) {
            // Handle exceptions with context
            throw new MobicoreApiException('Groups view failed: ' . $e->getMessage());
        }
    }

    /**
     * Retrieves all operators associated with the authenticated agent.
     *
     * This endpoint returns the list of operators that work under the current
     * agent user. Used for agent dashboards and operator management.
     *
     * @param Request $request The HTTP request (authorization required)
     * @return array Standardized API response with operators list
     * @throws MobicoreApiException When retrieval operation fails
     */
    public function viewAgentOperators(Request $request)
    {
        try {
            // Validate authorization header
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Extract auth header for API authentication
            $header = $request->header('Authorization');

            // Retrieve operators associated with current agent
            $response = $this->userEnrollmentService->viewAgentOperators($header);

            // Parse API response
            $decoded = json_decode($response);

            // Return operators data or error
            if (isset($decoded)) {
                return $this->formatSuccess($decoded, "Agents retrieved successfully");
            } else {
                return $this->formatError(400, "No operators found");
            }
        } catch (\Exception $e) {
            // Re-throw with context
            throw new MobicoreApiException('View agents failed: ' . $e->getMessage());
        }
    }

    /**
     * Resets an operator's PIN and sends reset notification.
     *
     * This endpoint allows agents to reset their operators' PINs. The system
     * generates a new PIN and sends it via email to the operator.
     *
     * Security consideration: Only agents should be able to reset their operators' PINs.
     *
     * @param Request $request The HTTP request containing agentOperator parameter
     * @return array Standardized API response with reset result
     * @throws MobicoreApiException When PIN reset operation fails
     */
    public function resetingOperatorsPinByAgent(Request $request)
    {
        try {
            // Check authentication
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Get authorization for API call
            $header = $request->header('Authorization');

            // Reset operator PIN and send notification
            $response = $this->userEnrollmentService->resetOperatorPinByAgent($request->agentOperator, $header);

            // Return success or error based on response
            if (isset($response)) {
                return $this->formatSuccess($response, "PIN reset successful");
            } else {
                return $this->formatError(500, "PIN reset failed");
            }
        } catch (\Exception $e) {
            // Handle and re-throw exceptions
            throw new MobicoreApiException('Reset PIN failed: ' . $e->getMessage());
        }
    }

    /**
     * Sets the main broker for a dependent agent.
     *
     * This endpoint establishes the primary broker relationship for a dependent agent.
     * The main broker has supervisory authority over the dependent agent.
     *
     * Note: Method description says "Groups retrieved successfully" but should be
     * "Broker set successfully". Should be updated for accuracy.
     *
     * @param Request $request The HTTP request containing dependant and broker parameters
     * @return array Standardized API response with broker setting result
     * @throws MobicoreApiException When broker setting operation fails
     */
    public function settingAgentMainBroker(Request $request)
    {
        try {
            // Validate user authentication
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Get authorization header
            $header = $request->header('Authorization');

            // Set main broker relationship for dependent agent
            $response = $this->userEnrollmentService->settingAgentMainBroker($request->dependant, $request->broker, $header);

            // Parse API response
            $decoded = json_decode($response);

            // Return success or error based on response
            if (isset($decoded)) {
                return $this->formatSuccess($decoded, "Main broker set successfully");
            } else {
                return $this->formatError(400, "Failed to set main broker");
            }
        } catch (\Exception $e) {
            // Handle exceptions with context
            throw new MobicoreApiException('Setting broker failed: ' . $e->getMessage());
        }
    }

    /**
     * Validates and retrieves user information by identifier.
     *
     * This endpoint looks up user details using various identifiers (username, ID, etc.).
     * Returns comprehensive user information including group membership and contact details.
     * Used for user verification and profile lookups.
     *
     * @param Request $request The HTTP request containing useridentify parameter
     * @return array Standardized API response with user details or error
     * @throws MobicoreApiException When user validation operation fails
     */
    public function userValidation(Request $request)
    {
        try {
            // Check authentication
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Get authorization header
            $header = $request->header('Authorization');

            // Validate user and retrieve details
            $response = $this->userEnrollmentService->userValidation($request->input("useridentify"), $header);

            // Decode API response
            $decoded = json_decode($response);

            // Process successful user lookup
            if (isset($decoded->id)) {
                // Extract user information safely
                $email = isset($decoded->email) ? $decoded->email : '';
                $userdetails = [
                    "id" => $decoded->id,
                    "names" => $decoded->display,
                    "email" => $email,
                    "group" => $decoded->group->internalName,
                    "phoneNumber" => $decoded->phones[0]->number
                ];
                return $this->formatSuccess($userdetails, "User validation successful");
            } else {
                // Handle various error conditions
                if (isset($decoded->code)) {
                    $mobicoreResponse1 = $decoded->code;
                    if ($mobicoreResponse1 == 'login') {
                        if (isset($decoded->passwordStatus)) {
                            $code = 102;
                            $codeDescription = "Password is temporarily blocked";
                        } elseif (isset($decoded->userStatus)) {
                            $code = 101;
                            $codeDescription = "User is " . $decoded->userStatus;
                        } else {
                            $code = 103;
                            $codeDescription = "Invalid authentication";
                        }
                    } else {
                        $code = 107;
                        $codeDescription = "User validation failed";
                    }
                    return $this->formatError($code, $codeDescription, $decoded);
                } else {
                    return $this->formatError(104, 'User not found', $decoded);
                }
            }
        } catch (\Exception $e) {
            // Re-throw with context
            throw new MobicoreApiException('User validation failed: ' . $e->getMessage());
        }
    }

    /**
     * Registers a new school by admin in the coreBank414 system.
     *
     * This endpoint creates school accounts with complete banking information,
     * school details, and administrative data. Schools can have associated students
     * and participate in educational payment systems.
     *
     * Note: This method should be refactored to use SchoolDTO and Form Request validation
     * for consistency with the refactored enrollment methods.
     *
     * @param Request $request The HTTP request containing school registration data
     * @return array Standardized API response with school creation result
     * @throws MobicoreApiException When school registration operation fails
     */
    public function RegisterNewSchoolByAdmin(Request $request)
    {
        try {
            // Validate authorization
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Define comprehensive validation rules for school data
            $rules = [
                "names" => 'required|string',
                "username" => 'required|string',
                "email" => 'required|email',
                "bank_name" => 'required|string',
                "bank_account_holder_name" => 'required|string',
                "bank_account_number" => 'required|string',
                "bank_branch" => 'required|string',
                "bank_code" => 'required|string',
                "schoolfees" => 'required|string',
                "schoolstatus" => 'required|string',
                "schoolcat" => 'required|string',
                "schoolcode" => 'required|string',
                "phoneNumber" => 'required|string',
                "province" => 'required|string',
                "district" => 'required|string',
                "sector" => 'required|string',
                "cell" => 'required|string',
                "village" => 'required|string',
                "city" => 'required|string'
            ];

            // Validate all school data
            if ($error = $this->validateRequest($rules, $request)) {
                return $error;
            }

            // Get authorization header
            $header = $request->header('Authorization');

            // Register school with all provided details
            // TODO: Refactor to use UserEnrollmentService.registerSchool() and SchoolDTO
            $response = $this->userEnrollmentService->registerNewSchoolByAdmin(
                $request->names,
                $request->username,
                $request->email,
                $request->bank_name,
                $request->bank_account_holder_name,
                $request->bank_account_number,
                $request->bank_branch,
                $request->bank_code,
                $request->schoolfees,
                $request->schoolstatus,
                $request->schoolcat,
                $request->schoolcode,
                $request->phoneNumber,
                $request->province,
                $request->district,
                $request->sector,
                $request->cell,
                $request->village,
                $request->city,
                $header
            );

            // Return standardized response
            return $this->handleApiResponse($response);
        } catch (\Exception $e) {
            // Handle registration failures
            throw new MobicoreApiException('School registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Onboards a student in the coreBank414 system.
     *
     * This endpoint handles student registration with guardian information,
     * school details, and identity verification. Uses the StudentOnboardingRequest
     * for validation and StudentDTO for data transfer. Students are onboarded
     * with registration authentication (not admin auth).
     *
     * @param StudentOnboardingRequest $request Validated request containing student data
     * @return array Standardized API response with student creation result
     * @throws MobicoreApiException When onboarding operation fails
     */
    public function StudentOnboarding(StudentOnboardingRequest $request)
    {
        try {
            // Check for required authorization header to ensure authenticated access
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Convert validated request data to DTO for type safety and clean data transfer
            $dto = $request->toDTO();

            // Call service layer to handle student onboarding (uses registration auth internally)
            $response = $this->userEnrollmentService->onboardStudent($dto);

            // Format and return standardized API response
            return $this->handleApiResponse($response);
        } catch (\Exception $e) {
            // Throw custom exception with context for error handling and logging
            throw new MobicoreApiException('Student onboarding failed: ' . $e->getMessage());
        }
    }

    /**
     * Registers a new parent/guardian by admin in the coreBank414 system.
     *
     * This endpoint creates parent accounts with personal information, next of kin details,
     * and identity verification. Parents can be associated with students for educational
     * payment and communication purposes.
     *
     * Note: Should be refactored to use ParentDTO and Form Request validation
     * for consistency with other enrollment methods.
     *
     * @param Request $request The HTTP request containing parent registration data
     * @return array Standardized API response with parent creation result
     * @throws MobicoreApiException When parent registration operation fails
     */
    public function RegisterNewParentByAdmin(Request $request)
    {
        try {
            // Validate admin authorization
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Define validation rules for parent data including next of kin
            $rules = [
                "names" => 'required|string',
                "username" => 'required|string',
                "email" => 'required|email',
                "firstname" => 'required|string',
                "lastname" => 'required|string',
                "nextkinname" => 'required|string',
                "nextkinrelation" => 'required|string',
                "identity_number" => 'required',
                "maritial_status" => 'required|string',
                "gender" => 'required|string',
                "date_of_birth" => 'required',
                "phoneNumber" => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                "province" => 'required|string',
                "district" => 'required|string',
                "sector" => 'required|string',
                "city" => 'required|string'
            ];

            // Validate all parent information
            if ($error = $this->validateRequest($rules, $request)) {
                return $error;
            }

            // Get authorization header for API call
            $header = $request->header('Authorization');

            // Register parent with complete profile
            // TODO: Refactor to use UserEnrollmentService.registerParent() and ParentDTO
            $response = $this->userEnrollmentService->registerNewParentByAdmin(
                $request->names,
                $request->username,
                $request->email,
                $request->firstname,
                $request->lastname,
                $request->nextkinname,
                $request->nextkinrelation,
                $request->identity_number,
                $request->maritial_status,
                $request->gender,
                $request->date_of_birth,
                $request->phoneNumber,
                $request->province,
                $request->district,
                $request->sector,
                $request->city,
                $header
            );

            // Return standardized API response
            return $this->handleApiResponse($response);
        } catch (\Exception $e) {
            // Handle registration exceptions
            throw new MobicoreApiException('Parent registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Enrolls a client user by admin in the coreBank414 system.
     *
     * This endpoint creates client accounts with admin privileges. Clients enrolled
     * by admin have Individual_clients group membership and can participate in
     * various financial services.
     *
     * Note: Uses legacy validation. Should be refactored to use ClientDTO and
     * Form Request validation for consistency.
     *
     * @param Request $request The HTTP request containing client enrollment data
     * @return array Standardized API response with client creation result
     * @throws MobicoreApiException When client enrollment operation fails
     */
    public function userClientEnrollment(Request $request)
    {
        try {
            // Check admin authorization
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Define validation rules for client data
            $rules = [
                "names" => 'required|string',
                "username" => 'required|string',
                "email" => 'required|email',
                "nationality" => 'required',
                "identity_type" => 'required',
                "identity_number" => 'required|string',
                "maritial_status" => 'required|string',
                "gender" => 'required|string',
                "date_of_birth" => 'required|string',
                "phoneNumber" => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                "province" => 'required|string',
                "district" => 'required|string',
                "sector" => 'required|string',
                "city" => 'required|string'
            ];

            // Validate client information
            if ($error = $this->validateRequest($rules, $request)) {
                return $error;
            }

            // Normalize marital status and gender values
            $marital_status = $request->maritial_status == 'M' ? 'married' : 'single';
            $gender = $request->gender == 'M' ? 'male' : 'female';

            // Get authorization header
            $header = $request->header('Authorization');

            // Enroll client with admin privileges
            // TODO: Refactor to use UserEnrollmentService.enrollClient() and ClientDTO
            $response = $this->userEnrollmentService->userClientEnrollmentWithAdmin(
                $request->names,
                $request->username,
                $request->email,
                $request->identity_type,
                $request->identity_number,
                $marital_status,
                $gender,
                $request->date_of_birth,
                $request->phoneNumber,
                $request->province,
                $request->district,
                $request->sector,
                $request->city,
                $header
            );

            // Return standardized response
            return $this->handleApiResponse($response);
        } catch (\Exception $e) {
            // Handle enrollment failures
            throw new MobicoreApiException('Client enrollment failed: ' . $e->getMessage());
        }
    }

    /**
     * Enrolls a client user by agent in the coreBank414 system.
     *
     * This endpoint allows agents to enroll clients under their supervision.
     * Clients enrolled by agents are marked as members (asMember = true) and
     * are associated with the enrolling agent.
     *
     * Note: Similar to admin enrollment but with agent permissions and membership flag.
     * Should be consolidated with admin enrollment using different DTO configurations.
     *
     * @param Request $request The HTTP request containing client enrollment data
     * @return array Standardized API response with client creation result
     * @throws MobicoreApiException When client enrollment operation fails
     */
    public function userClientEnrollmentAgent(Request $request)
    {
        try {
            // Validate agent authorization
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Define same validation rules as admin enrollment
            $rules = [
                "names" => 'required|string',
                "username" => 'required|string',
                "email" => 'required|email',
                "nationality" => 'required',
                "identity_type" => 'required',
                "identity_number" => 'required|string',
                "maritial_status" => 'required|string',
                "gender" => 'required|string',
                "date_of_birth" => 'required|string',
                "phoneNumber" => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                "province" => 'required|string',
                "district" => 'required|string',
                "sector" => 'required|string',
                "city" => 'required|string'
            ];

            // Validate client data
            if ($error = $this->validateRequest($rules, $request)) {
                return $error;
            }

            // Normalize status values
            $marital_status = $request->maritial_status == 'M' ? 'married' : 'single';
            $gender = $request->gender == 'M' ? 'male' : 'female';

            // Get agent authorization
            $header = $request->header('Authorization');

            // Enroll client with agent association (as member)
            // TODO: Refactor to use UserEnrollmentService.enrollClient() with member flag
            $response = $this->userEnrollmentService->userClientEnrollmentWithAgent(
                $request->names,
                $request->username,
                $request->email,
                $request->identity_type,
                $request->identity_number,
                $marital_status,
                $gender,
                $request->date_of_birth,
                $request->phoneNumber,
                $request->province,
                $request->district,
                $request->sector,
                $request->city,
                $header
            );

            // Return standardized response
            return $this->handleApiResponse($response);
        } catch (\Exception $e) {
            // Handle enrollment exceptions
            throw new MobicoreApiException('Client enrollment agent failed: ' . $e->getMessage());
        }
    }

    /**
     * Enrolls a dependent client under a specific broker.
     *
     * This endpoint creates client accounts that are explicitly linked to a broker.
     * First validates the broker ID, then enrolls the client with broker association.
     * Used for clients who need specific broker relationships.
     *
     * @param Request $request The HTTP request containing client and broker data
     * @return array Standardized API response with client creation result or broker validation error
     * @throws MobicoreApiException When dependent enrollment operation fails
     */
    public function userClientDependentEnrollment(Request $request)
    {
        try {
            // Check authorization
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Define validation rules including broker requirement
            $rules = [
                "names" => 'required|string',
                "username" => 'required|string',
                "email" => 'required|email',
                "identity_type" => 'required',
                "identity_number" => 'required|string',
                "maritial_status" => 'required|string',
                "gender" => 'required|string',
                "date_of_birth" => 'required|string',
                "group" => 'required|string',
                "brokerid" => 'required',
                "phoneNumber" => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                "province" => 'required|string',
                "district" => 'required|string',
                "sector" => 'required|string',
                "city" => 'required|string'
            ];

            // Validate client and broker data
            if ($error = $this->validateRequest($rules, $request)) {
                return $error;
            }

            // First validate that the broker exists
            $brokerValidationResponse = $this->userEnrollmentService->brokerIdValidation($request->input("brokerid"));
            $brokerData = json_decode($brokerValidationResponse);

            // Check if broker validation was successful
            if (isset($brokerData[0]->id)) {
                $brokerId = $brokerData[0]->id;

                // Normalize status values
                $marital_status = $request->maritial_status == 'M' ? 'married' : 'single';
                $gender = $request->gender == 'M' ? 'male' : 'female';

                // Enroll client with validated broker association
                $response = $this->userEnrollmentService->userClientDependentEnrollment(
                    $request->names,
                    $request->username,
                    $request->email,
                    $request->identity_type,
                    $request->identity_number,
                    $marital_status,
                    $gender,
                    $request->date_of_birth,
                    $request->group,
                    $brokerId,
                    $request->phoneNumber,
                    $request->province,
                    $request->district,
                    $request->sector,
                    $request->city
                );

                // Process enrollment response
                $decoded = json_decode($response);
                if (isset($decoded->user->id)) {
                    return $this->formatSuccess([
                        "id" => $decoded->user->id,
                        "display" => $decoded->user->display,
                        "principal" => $decoded->principals ?? null
                    ]);
                } else {
                    return $this->handleApiResponse($response);
                }
            } else {
                // Return error if broker validation failed
                return $this->formatError(104, "Invalid broker ID", $brokerValidationResponse);
            }
        } catch (\Exception $e) {
            // Handle enrollment failures
            throw new MobicoreApiException('Dependent enrollment failed: ' . $e->getMessage());
        }
    }

    /**
     * Enrolls a pending client with minimal information.
     *
     * This endpoint creates basic client accounts that are marked as "pending_clients_group".
     * These accounts have limited functionality and may require additional verification
     * before becoming fully active clients. Uses a default broker ID.
     *
     * @param Request $request The HTTP request containing basic client information
     * @return array Standardized API response with pending client creation result
     * @throws MobicoreApiException When pending client enrollment operation fails
     */
    public function userPendingClientEnrollment(Request $request)
    {
        try {
            // Validate authorization (may not be strictly required for pending clients)
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Define minimal validation rules for pending clients
            $rules = [
                "names" => 'required|string',
                "identity_number" => 'required|string',
                "maritial_status" => 'required|string',
                "gender" => 'required|string',
                "date_of_birth" => 'required|string',
                "province" => 'required|string',
                "district" => 'required|string',
                "sector" => 'required|string',
                "cell" => 'required|string',
                "village" => 'required|string'
            ];

            // Validate basic client data
            if ($error = $this->validateRequest($rules, $request)) {
                return $error;
            }

            // Normalize status values
            $marital_status = $request->maritial_status == 'M' ? 'married' : 'single';
            $gender = $request->gender == 'M' ? 'male' : 'female';

            // Create pending client account (no authorization header needed)
            $response = $this->userEnrollmentService->userPendingClientEnrollment(
                $request->names,
                $request->identity_number,
                $marital_status,
                $gender,
                $request->date_of_birth,
                $request->province,
                $request->district,
                $request->sector,
                $request->cell,
                $request->village
            );

            // Return standardized response
            return $this->handleApiResponse($response);
        } catch (\Exception $e) {
            // Handle enrollment exceptions
            throw new MobicoreApiException('Pending client enrollment failed: ' . $e->getMessage());
        }
    }

    /**
     * Validates a broker ID and returns broker information.
     *
     * This endpoint checks if a broker exists in the system and returns their details.
     * Used before enrolling dependent clients to ensure broker validity.
     * Also used for broker lookups in various enrollment processes.
     *
     * @param Request $request The HTTP request containing brokercode parameter
     * @return array Standardized API response with broker details or validation error
     * @throws MobicoreApiException When broker validation operation fails
     */
    public function brokerIdValidation(Request $request)
    {
        try {
            // Check user authorization
            if ($error = $this->checkAuthorization($request)) {
                return $error;
            }

            // Validate broker by code/username
            $response = $this->userEnrollmentService->brokerIdValidation($request->input("brokercode"));
            $decoded = json_decode($response);

            // Return broker details if found
            if (isset($decoded[0]->id)) {
                return $this->formatSuccess([
                    "id" => $decoded[0]->id,
                    "display" => $decoded[0]->display,
                    "principal" => $decoded[0]->principals ?? null
                ]);
            } else {
                // Return error if broker not found
                return $this->formatError(104, "Broker not found", $response);
            }
        } catch (\Exception $e) {
            // Handle validation failures
            throw new MobicoreApiException('Broker validation failed: ' . $e->getMessage());
        }
    }

    /**
     * Enrolls an external client using only phone number.
     *
     * This endpoint creates minimal client accounts for external systems integration.
     * Only requires a phone number and creates accounts in the "external_clients_group".
     * These accounts have limited functionality and are typically used for external API access.
     *
     * Note: No authorization header required, making it accessible for external integrations.
     *
     * @param Request $request The HTTP request containing clientPhone parameter
     * @return array Standardized API response with external client creation result
     * @throws MobicoreApiException When external client enrollment operation fails
     */
    public function externalClientsGroupEnrollment(Request $request)
    {
        try {
            // Define minimal validation for phone number only
            $rules = [
                "clientPhone" => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
            ];

            // Validate phone number format
            if ($error = $this->validateRequest($rules, $request)) {
                return $error;
            }

            // Create external client account
            $response = $this->userEnrollmentService->externalClientsGroupEnrollment($request->clientPhone);
            $decoded = json_decode($response);

            // Handle successful enrollment
            if (isset($decoded->user)) {
                return [
                    "responseCode" => 100,
                    "communicationStatus" => 'SUCCESS',
                    "codeDescription" => $decoded->user->display,
                    "data" => $decoded->user->id,
                    "responseDate" => date('Y-m-d H:i:s')
                ];
            } else {
                // Handle various error conditions with detailed mapping
                if (isset($decoded->code)) {
                    $mobicoreResponse1 = $decoded->code;
                    if ($mobicoreResponse1 == 'login') {
                        if (isset($decoded->passwordStatus)) {
                            $code = 102;
                            $codeDescription = "Password is temporarily blocked";
                        } elseif (isset($decoded->userStatus)) {
                            $code = 101;
                            $codeDescription = "User is " . $decoded->userStatus;
                        } else {
                            $code = 103;
                            $codeDescription = "Invalid authentication";
                        }
                    } elseif ($mobicoreResponse1 == 'insufficientBalance') {
                        $code = 106;
                        $codeDescription = "Insufficient Balance";
                    } else {
                        if (isset($decoded->customFieldErrors)) {
                            $code = 105;
                            $codeDescription = "Custom Field Errors";
                        }
                        if (isset($decoded->propertyErrors)) {
                            $code = 105;
                            $codeDescription = json_encode($decoded->propertyErrors);
                            // Special handling for duplicate phone numbers
                            if ($codeDescription == $decoded->propertyErrors->username[0]) {
                                $code = 101;
                                $codeDescription = "This phone is already in the system.";
                            }
                        } else {
                            $code = 107;
                            $codeDescription = "FAILURE";
                        }
                    }
                    return $this->formatError($code, $codeDescription, $decoded);
                } else {
                    return $this->formatError(105, 'External client enrollment failed', $decoded);
                }
            }
        } catch (\Exception $e) {
            // Handle enrollment exceptions
            throw new MobicoreApiException('External group enrollment failed: ' . $e->getMessage());
        }
    }

    /**
     * Performs simple client enrollment with minimal taxpayer information.
     *
     * This endpoint creates basic client accounts for tax collection purposes.
     * Requires only phone number, taxpayer name, and optional tax ID.
     * Used for quick client registration in tax-related workflows.
     *
     * @param Request $request The HTTP request containing phoneNumber and taxpayername
     * @return array Standardized API response with simple client creation result
     * @throws MobicoreApiException When simple client enrollment operation fails
     */
    public function simpleClientsEnrollment(Request $request)
    {
        try {
            // Define minimal validation rules
            $rules = [
                "phoneNumber" => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                "taxpayername" => 'required'
            ];

            // Validate basic requirements
            if ($error = $this->validateRequest($rules, $request)) {
                return $error;
            }

            // Get authorization header for API calls
            $authorisation = $request->header('Authorization');

            // Create simple client account
            $response = $this->userEnrollmentService->simpleClientsEnrollment(
                $request->phoneNumber,
                $request->taxpayername,
                $request->username,
                $request->taxidentificationnumber,
                $authorisation
            );

            // Decode and process response
            $decoded = json_decode($response);

            // Handle successful enrollment
            if (isset($decoded->user)) {
                return [
                    "responseCode" => 100,
                    "communicationStatus" => 'SUCCESS',
                    "codeDescription" => $decoded->user->display,
                    "data" => $decoded->user->id,
                    "responseDate" => date('Y-m-d H:i:s')
                ];
            } else {
                // Handle various error conditions
                if (isset($decoded->code)) {
                    $mobicoreResponse1 = $decoded->code;
                    if ($mobicoreResponse1 == 'login') {
                        if (isset($decoded->passwordStatus)) {
                            $code = 102;
                            $codeDescription = "Password is temporarily blocked";
                        } elseif (isset($decoded->userStatus)) {
                            $code = 101;
                            $codeDescription = "User is " . $decoded->userStatus;
                        } else {
                            $code = 103;
                            $codeDescription = "Invalid authentication";
                        }
                    } elseif ($mobicoreResponse1 == 'insufficientBalance') {
                        $code = 106;
                        $codeDescription = "Insufficient Balance";
                    } else {
                        if (isset($decoded->customFieldErrors)) {
                            $code = 105;
                            $codeDescription = "Custom Field Errors";
                        }
                        if (isset($decoded->propertyErrors)) {
                            if (isset($decoded->propertyErrors->username[0])) {
                                $code = 105;
                                $codeDescription = $decoded->propertyErrors->username[0];
                            }
                        } else {
                            $code = 107;
                            $codeDescription = "FAILURE";
                        }
                    }
                    return $this->formatError($code, $codeDescription, $decoded);
                } else {
                    return $this->formatError(105, 'Simple client enrollment failed', $decoded);
                }
            }
        } catch (\Exception $e) {
            // Handle enrollment failures
            throw new MobicoreApiException('Simple client enrollment failed: ' . $e->getMessage());
        }
    }
}