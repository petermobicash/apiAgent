<?php

/**
 * Modular Laravel API Routes (v4.14.01)
 *
 * This master route file loads all individual route groups from /routes/api/*.php
 * Each file corresponds to a feature/service module such as user enrollment, utilities, RRA, etc.
 */

use Illuminate\Support\Facades\Route;

// Base Unauthorized Endpoint
Route::post('/', fn () => response()->json([
    'responseCode' => '401',
    'status' => 'fail',
    'message' => 'Unauthorized'
], 401));

// Automatically load all route groups
foreach ([
    'user.php',
    'float.php',
    'cbhi.php',
    'rra.php',
    'rnit.php',
    'ltss.php',
    'electricity.php',
    'utilities.php',
    'banking.php',
    'mobischool.php',
    'ria.php',
    'giramahoro.php',
] as $file) {
    require __DIR__ . "/api/{$file}";
}
