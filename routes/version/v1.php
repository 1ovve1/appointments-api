<?php

use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\SpecialistController;
use App\Http\Controllers\Api\V1\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('specialists', SpecialistController::class);
    Route::apiResource('appointments', AppointmentController::class);
});
