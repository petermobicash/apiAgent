<?php

namespace App\Http\Controllers\API\coreBank414\UserAccess;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\coreBank414\CoreBank414BaseController;
use Illuminate\Support\Facades\Validator;
use App\Services\CoreBanking\UsersAccess;
use Exception;

/**
 * @group User Management
 *
 * API endpoints for managing user authentication, including password changes and forgotten password requests.
 *
 * This controller adheres to SOLID principles by separating concerns, using dependency injection,
 * and providing clear, maintainable code with proper error handling and security measures.
 */
class UserAccessController extends CoreBank414BaseController
{
    private UsersAccess $usersAccess;

    /**
     * Constructor to inject dependencies.
     *
     * @param UsersAccess $usersAccess The service for user access operations.
     */
    public function __construct(UsersAccess $usersAccess)
    {
        $this->usersAccess = $usersAccess;
    }

    /**
     * Change the user's password.
     *
     * Validates input, checks authorization, and updates the password via the UsersAccess service.
     *
     * @bodyParam oldPassword string required The old password.
     * @bodyParam newPassword string required The new password.
     * @bodyParam newPassword_confirmation string required The new password confirmation.
     * @response 200 {"responseCode":100,"status":"success","message":"SUCCESS","data":{"id":1,"display":"user","principal":{}},"responseDate":"2023-01-01T00:00:00.000000Z"}
     * @param Request $request The HTTP request containing oldPassword, newPassword, newPasswordConfirmation.
     * @return JsonResponse The response with success or error details.
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            // Check for authorization header
            if (!$request->header('Authorization')) {
                return $this->sendCoreBank414Error('Authorization required.', 401);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'oldPassword' => 'required|string|min:8',
                'newPassword' => 'required|string|min:8|confirmed', // Assumes newPassword_confirmation field
                'newPassword_confirmation' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->sendCoreBank414Error($validator->errors()->toJson(), 400);
            }

            // Ensure new password matches confirmation
            if ($request->newPassword !== $request->newPassword_confirmation) {
                return $this->sendCoreBank414Error('New password confirmation does not match.', 400);
            }

            // Call service to change password
            $header = $request->header('Authorization');
            $response = $this->usersAccess->changePassword(
                $request->oldPassword,
                $request->newPassword,
                $request->newPassword_confirmation,
                $header
            );

            $decodedResponse = json_decode($response);

            if (isset($decodedResponse->user)) {
                return $this->sendCoreBank414Success($this->formatUserInfo($decodedResponse->user, $decodedResponse->principals));
            } else {
                return $this->sendCoreBank414Error($decodedResponse ?? 'Password change failed.', 400);
            }
        } catch (Exception $e) {
            // Log the exception if needed
            return $this->sendCoreBank414Error('An error occurred while changing the password.', 500);
        }
    }

    /**
     * Handle forgotten password request.
     *
     * Validates input and sends a password reset request via the UsersAccess service.
     *
     * @bodyParam user string required The user identifier.
     * @bodyParam sendMedium string required The email to send the reset request.
     * @response 200 {"responseCode":100,"status":"success","message":"SUCCESS","data":{"id":1,"display":"user","principal":{}},"responseDate":"2023-01-01T00:00:00.000000Z"}
     * @param Request $request The HTTP request containing user and sendMedium.
     * @return JsonResponse The response with success or error details.
     */
    public function forgettenpasswordRequest(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'user' => 'required|string',
                'sendMedium' => 'required|email',
            ]);

            if ($validator->fails()) {
                return $this->sendCoreBank414Error($validator->errors()->toJson(), 400);
            }

            // Call service for forgotten password
            $response = $this->usersAccess->forgettenpasswordRequest($request->user, $request->sendMedium);

            $decodedResponse = json_decode($response);

            if (isset($decodedResponse->user)) {
                return $this->sendCoreBank414Success($this->formatUserInfo($decodedResponse->user, $decodedResponse->principals));
            } else {
                return $this->sendCoreBank414Error($decodedResponse ?? 'Forgotten password request failed.', 400);
            }
        } catch (Exception $e) {
            // Log the exception if needed
            return $this->sendCoreBank414Error('An error occurred while processing the forgotten password request.', 500);
        }
    }

    /**
     * Handle forgotten password change.
     *
     * Validates input and changes the password using the reset code via the UsersAccess service.
     *
     * @bodyParam user string required The user identifier.
     * @bodyParam code string required The reset code.
     * @bodyParam newPassword string required The new password.
     * @bodyParam newPassword_confirmation string required The new password confirmation.
     * @response 200 {"responseCode":100,"status":"success","message":"SUCCESS","data":{"id":1,"display":"user","principal":{}},"responseDate":"2023-01-01T00:00:00.000000Z"}
     * @param Request $request The HTTP request containing user, code, newPassword, newPasswordConfirmation.
     * @return JsonResponse The response with success or error details.
     */
    public function forgettenpasswordChange(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'user' => 'required|string',
                'code' => 'required|string',
                'newPassword' => 'required|string|min:8|confirmed',
                'newPassword_confirmation' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->sendCoreBank414Error($validator->errors()->toJson(), 400);
            }

            // Ensure new password matches confirmation
            if ($request->newPassword !== $request->newPassword_confirmation) {
                return $this->sendCoreBank414Error('New password confirmation does not match.', 400);
            }

            // Call service for forgotten password change
            $response = $this->usersAccess->forgettenpasswordChange(
                $request->user,
                $request->code,
                $request->newPassword,
                $request->newPassword_confirmation
            );

            $decodedResponse = json_decode($response);

            if (isset($decodedResponse->user)) {
                return $this->sendCoreBank414Success($this->formatUserInfo($decodedResponse->user, $decodedResponse->principals));
            } else {
                return $this->sendCoreBank414Error($decodedResponse ?? 'Forgotten password change failed.', 400);
            }
        } catch (Exception $e) {
            // Log the exception if needed
            return $this->sendCoreBank414Error('An error occurred while processing the forgotten password change.', 500);
        }
    }

    /**
     * Generate first time password.
     *
     * Generates a first time password for the authenticated user via the UsersAccess service.
     *
     * @response 200 {"responseCode":100,"status":"success","message":"SUCCESS","data":{},"responseDate":"2023-01-01T00:00:00.000000Z"}
     * @param Request $request The HTTP request.
     * @return JsonResponse The response with success or error details.
     */
    public function generateFirstTimePassword(Request $request): JsonResponse
    {
        try {
            // Check for authorization header
            if (!$request->header('Authorization')) {
                return $this->sendCoreBank414Error('Authorization required.', 401);
            }

            // Call service to generate first time password
            $header = $request->header('Authorization');
            $response = $this->usersAccess->generateFirstTimePassword($header);

            $decodedResponse = json_decode($response);

            if ($decodedResponse) {
                return $this->sendCoreBank414Success($decodedResponse);
            } else {
                return $this->sendCoreBank414Error('First time password generation failed.', 400);
            }
        } catch (Exception $e) {
            // Log the exception if needed
            return $this->sendCoreBank414Error('An error occurred while generating first time password.', 500);
        }
    }

    /**
     * Authenticate user.
     *
     * Checks the authentication status of the user via the UsersAccess service.
     *
     * @response 200 {"responseCode":100,"status":"success","message":"SUCCESS","data":{},"responseDate":"2023-01-01T00:00:00.000000Z"}
     * @param Request $request The HTTP request.
     * @return JsonResponse The response with success or error details.
     */
    public function authentification(Request $request): JsonResponse
    {
        try {
            // Check for authorization header
            if (!$request->header('Authorization')) {
                return $this->sendCoreBank414Error('Authorization required.', 401);
            }

            // Call service to authenticate
            $header = $request->header('Authorization');
            $response = $this->usersAccess->authentication($header);

            $decodedResponse = json_decode($response);

            if ($decodedResponse) {
                return $this->sendCoreBank414Success($decodedResponse);
            } else {
                return $this->sendCoreBank414Error('Authentication failed.', 401);
            }
        } catch (Exception $e) {
            // Log the exception if needed
            return $this->sendCoreBank414Error('An error occurred during authentication.', 500);
        }
    }

    /**
     * Activate session token.
     *
     * Activates a session token with a PIN via the UsersAccess service.
     *
     * @bodyParam pin string required The PIN for activation.
     * @response 200 {"responseCode":100,"status":"success","message":"Session activated successfully.","data":{"message":"Session activated successfully."},"responseDate":"2023-01-01T00:00:00.000000Z"}
     * @param Request $request The HTTP request containing pin and Session-Token header.
     * @return JsonResponse The response with success or error details.
     */
    public function tokenSessionActivation(Request $request): JsonResponse
    {
        try {
            // Check for Session-Token header
            if (!$request->header('Session-Token')) {
                return $this->sendCoreBank414Error('Session-Token required.', 401);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'pin' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->sendCoreBank414Error($validator->errors()->toJson(), 400);
            }

            // Call service to activate session token
            $sessionToken = $request->header('Session-Token');
            $response = $this->usersAccess->tokenSessionActivation($sessionToken, $request->pin);

            if ($response == 200) {
                return $this->sendCoreBank414Success(['message' => 'Session activated successfully.']);
            } else {
                return $this->sendCoreBank414Error('Session activation failed.', $response);
            }
        } catch (Exception $e) {
            // Log the exception if needed
            return $this->sendCoreBank414Error('An error occurred during session activation.', 500);
        }
    }

    /**
     * Authenticate session.
     *
     * Creates or authenticates a session via the UsersAccess service.
     *
     * @response 200 {"responseCode":100,"status":"success","message":"SUCCESS","data":{},"responseDate":"2023-01-01T00:00:00.000000Z"}
     * @param Request $request The HTTP request.
     * @return JsonResponse The response with success or error details.
     */
    public function authentificationtsession(Request $request): JsonResponse
    {
        try {
            // Check for authorization header
            if (!$request->header('Authorization')) {
                return $this->sendCoreBank414Error('Authorization required.', 401);
            }

            // Call service to authenticate session
            $header = $request->header('Authorization');
            $response = $this->usersAccess->authentificationtsession($header);

            $decodedResponse = json_decode($response);

            if ($decodedResponse) {
                return $this->sendCoreBank414Success($decodedResponse);
            } else {
                return $this->sendCoreBank414Error('Session authentication failed.', 401);
            }
        } catch (Exception $e) {
            // Log the exception if needed
            return $this->sendCoreBank414Error('An error occurred during session authentication.', 500);
        }
    }

    /**
     * Get user group appartenance.
     *
     * Retrieves the user's group membership via the UsersAccess service.
     *
     * @param Request $request The HTTP request.
     * @return JsonResponse The response with success or error details.
     */
    public function groupAppartenance(Request $request): JsonResponse
    {
        try {
            // Check for authorization header
            if (!$request->header('Authorization')) {
                return $this->sendCoreBank414Error('Authorization required.', 401);
            }

            // Call service to get group appartenance
            $header = $request->header('Authorization');
            $response = $this->usersAccess->groupAppartenance($header);

            $decodedResponse = json_decode($response);

            if ($decodedResponse) {
                return $this->sendCoreBank414Success($decodedResponse);
            } else {
                return $this->sendCoreBank414Error('Failed to retrieve group appartenance.', 400);
            }
        } catch (Exception $e) {
            // Log the exception if needed
            return $this->sendCoreBank414Error('An error occurred while retrieving group appartenance.', 500);
        }
    }

    /**
     * Get main agent group.
     *
     * Retrieves the main agent group for a given agent ID via the UsersAccess service.
     *
     * @param Request $request The HTTP request containing agentId.
     * @return JsonResponse The response with success or error details.
     */
    public function mainAgentGroup(Request $request): JsonResponse
    {
        try {
            // Check for authorization header
            if (!$request->header('Authorization')) {
                return $this->sendCoreBank414Error('Authorization required.', 401);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'agentId' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->sendCoreBank414Error($validator->errors()->toJson(), 400);
            }

            // Call service to get main agent group
            $header = $request->header('Authorization');
            $response = $this->usersAccess->mainAgentGroup($request->agentId, $header);

            $decodedResponse = json_decode($response);

            if ($decodedResponse) {
                return $this->sendCoreBank414Success($decodedResponse);
            } else {
                return $this->sendCoreBank414Error('Failed to retrieve main agent group.', 400);
            }
        } catch (Exception $e) {
            // Log the exception if needed
            return $this->sendCoreBank414Error('An error occurred while retrieving main agent group.', 500);
        }
    }

    /**
     * Search user by admin.
     *
     * Searches for a user by account via the UsersAccess service.
     *
     * @param Request $request The HTTP request containing userAccount.
     * @return JsonResponse The response with success or error details.
     */
    public function userSearchByAdmin(Request $request): JsonResponse
    {
        try {
            // Check for authorization header
            if (!$request->header('Authorization')) {
                return $this->sendCoreBank414Error('Authorization required.', 401);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'userAccount' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->sendCoreBank414Error($validator->errors()->toJson(), 400);
            }

            // Call service to search user
            $header = $request->header('Authorization');
            $response = $this->usersAccess->userSearchByAdmin($request->userAccount, $header);

            $decodedResponse = json_decode($response);

            if ($decodedResponse) {
                return $this->sendCoreBank414Success($decodedResponse);
            } else {
                return $this->sendCoreBank414Error('User not found.', 404);
            }
        } catch (Exception $e) {
            // Log the exception if needed
            return $this->sendCoreBank414Error('An error occurred while searching for user.', 500);
        }
    }

    /**
     * Reset user PIN.
     *
     * Resets the PIN for a user account via the UsersAccess service.
     *
     * @bodyParam account string required The user account.
     * @bodyParam type string required The type (pin or user_password).
     * @response 200 {"responseCode":100,"status":"success","message":"PIN reset successfully.","data":{"message":"PIN reset successfully."},"responseDate":"2023-01-01T00:00:00.000000Z"}
     * @param Request $request The HTTP request containing account and type.
     * @return JsonResponse The response with success or error details.
     */
    public function resetUserPin(Request $request): JsonResponse
    {
        try {
            // Check for authorization header
            if (!$request->header('Authorization')) {
                return $this->sendCoreBank414Error('Authorization required.', 401);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'account' => 'required|string',
                'type' => 'required|string|in:pin,user_password',
            ]);

            if ($validator->fails()) {
                return $this->sendCoreBank414Error($validator->errors()->toJson(), 400);
            }

            // Call service to reset user PIN
            $header = $request->header('Authorization');
            $response = $this->usersAccess->resetUserPin($request->account, $header, $request->type);

            if ($response == 200) {
                return $this->sendCoreBank414Success(['message' => 'PIN reset successfully.']);
            } else {
                return $this->sendCoreBank414Error('PIN reset failed.', $response);
            }
        } catch (Exception $e) {
            // Log the exception if needed
            return $this->sendCoreBank414Error('An error occurred while resetting PIN.', 500);
        }
    }

    /**
     * Change user PIN.
     *
     * Changes the PIN or password for a user account via the UsersAccess service.
     *
     * @bodyParam account string required The user account.
     * @bodyParam oldPassword string required The old password.
     * @bodyParam newPassword string required The new password.
     * @bodyParam newPassword_confirmation string required The new password confirmation.
     * @bodyParam type string required The type (pin or user_password).
     * @response 200 {"responseCode":100,"status":"success","message":"SUCCESS","data":{},"responseDate":"2023-01-01T00:00:00.000000Z"}
     * @param Request $request The HTTP request containing account, oldPassword, newPassword, newPassword_confirmation, type.
     * @return JsonResponse The response with success or error details.
     */
    public function changeUserPin(Request $request): JsonResponse
    {
        try {
            // Check for authorization header
            if (!$request->header('Authorization')) {
                return $this->sendCoreBank414Error('Authorization required.', 401);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'account' => 'required|string',
                'oldPassword' => 'required|string',
                'newPassword' => 'required|string|min:8|confirmed',
                'newPassword_confirmation' => 'required|string',
                'type' => 'required|string|in:pin,user_password',
            ]);

            if ($validator->fails()) {
                return $this->sendCoreBank414Error($validator->errors()->toJson(), 400);
            }

            // Ensure new password matches confirmation
            if ($request->newPassword !== $request->newPassword_confirmation) {
                return $this->sendCoreBank414Error('New password confirmation does not match.', 400);
            }

            // Call service to change user PIN
            $header = $request->header('Authorization');
            $response = $this->usersAccess->changeUserPin(
                $request->account,
                $header,
                $request->oldPassword,
                $request->newPassword,
                $request->newPassword_confirmation,
                $request->type
            );

            if ($response) {
                $decodedResponse = json_decode($response);
                if ($decodedResponse) {
                    return $this->sendCoreBank414Success($decodedResponse);
                } else {
                    return $this->sendCoreBank414Error('PIN change failed.', 400);
                }
            } else {
                return $this->sendCoreBank414Success(['message' => 'PIN changed successfully.']);
            }
        } catch (Exception $e) {
            // Log the exception if needed
            return $this->sendCoreBank414Error('An error occurred while changing PIN.', 500);
        }
    }

    /**
     * Format user information for response.
     *
     * @param object $user The user object.
     * @param mixed $principals The principals data.
     * @return array Formatted user info.
     */
    private function formatUserInfo(object $user, $principals): array
    {
        return [
            'id' => $user->id,
            'display' => $user->display,
            'principal' => $principals,
        ];
    }

}