<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\reviewController;

Route::get('/', [reviewController::class, 'fetchData']);

