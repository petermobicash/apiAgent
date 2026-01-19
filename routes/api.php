<?php

/**
 * Modular Laravel API Routes (v4.14.01)
 *
 * This master route file loads all individual route groups from /routes/api/*.php
 * Each file corresponds to a feature/service module such as user enrollment, utilities, RRA, etc.
 *
 * Improvements:
 * - Added error handling for file loading to prevent fatal errors if a module file is missing.
 * - Enhanced security by adding throttle middleware to prevent abuse.
 * - Improved maintainability by organizing module files in a structured array.
 * - Added logging for debugging route loading issues.
 */

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

// Base Unauthorized Endpoint with improved response
Route::post('/', function () {
    // Added input validation: ensure no unexpected data in request
    if (!empty(request()->all())) {
        return response()->json([
            'responseCode' => '400',
            'status' => 'fail',
            'message' => 'Bad Request: Unexpected input'
        ], 400);
    }
    return response()->json([
        'responseCode' => '401',
        'status' => 'fail',
        'message' => 'Unauthorized'
    ], 401);
})->middleware('throttle:60,1'); // Security: Rate limiting to prevent brute force

// Define modules with descriptions for better maintainability
$modules = [
    'user.php' => 'User registration and authentication routes',
    'float.php' => 'Float deposit and top-up routes',
    'cbhi.php' => 'CBHI collection and payment routes',
    'rra.php' => 'RRA tax collection routes',
    'rnit.php' => 'RNIT contribution routes',
    'ltss.php' => 'LTSS contribution routes',
    'electricity.php' => 'Electricity subscription and payment routes',
    'utilities.php' => 'Utility services routes',
    'banking.php' => 'Banking transaction routes',
    'mobischool.php' => 'MobiSchool transaction routes',
    'ria.php' => 'RIA remittance routes',
    'giramahoro.php' => 'Giramahoro payment routes',
    'momo.php' => 'MoMo transaction routes',
    'merchant.php' => 'Merchant payment collection routes',
    'payment-notification.php' => 'Payment notification routes',
    'mobicore.php' => 'Mobicore API endpoints for account management and payments',
    'health.php' => 'Health check monitoring routes',
];

// Load route groups with error handling and logging
foreach ($modules as $file => $description) {
    $filePath = __DIR__ . "/api/{$file}";
    if (!file_exists($filePath)) {
        // Error handling: Log missing file instead of crashing
        Log::error("API Route file missing: {$file} - {$description}");
        continue;
    }
    try {
        require $filePath;
        Log::info("Loaded API routes: {$file} - {$description}");
    } catch (\Throwable $e) {
        // Error handling: Catch and log exceptions during route loading
        Log::error("Failed to load API routes: {$file} - {$description}. Error: " . $e->getMessage());
    }
}
