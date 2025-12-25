<?php

use Illuminate\Support\Facades\Route;
// TODO 11: Import PageController
use App\Http\Controllers\PageController;

// TODO 12: Định nghĩa các Route
Route::get('/', [PageController::class, 'showHomepage']);
Route::get('/about', [PageController::class, 'showHomepage']);