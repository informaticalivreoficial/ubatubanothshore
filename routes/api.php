<?php

use App\Http\Controllers\Api\PropertyReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::prefix('properties/{property}')->group(function () 
    {
        Route::get('/calendar', [PropertyReservationController::class, 'calendar']);

        Route::post('/check-availability', [PropertyReservationController::class, 'checkAvailability']);

        Route::post('/calculate', [PropertyReservationController::class, 'calculate']);

        Route::post('/reservations', [PropertyReservationController::class, 'store']);
    });
});