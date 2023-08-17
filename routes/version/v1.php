<?php

use App\Http\Controllers\Api\V1\ClientAppointmentsController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\SpecialistAppointmentsController;
use App\Http\Controllers\Api\V1\SpecialistController;
use App\Http\Controllers\Api\V1\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('specialists', SpecialistController::class);
    Route::apiResource('appointments', AppointmentController::class);

    Route::apiResource('clients.appointments', ClientAppointmentsController::class)
        ->only(['index', 'show', 'store', 'destroy']);

    Route::apiResource('specialists.appointments', SpecialistAppointmentsController::class)
        ->only(['index', 'show', 'destroy']);
});
