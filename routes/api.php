<?php

use App\Http\Controllers\Api\v1\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('imoveis', [PropertyController::class, 'index']);