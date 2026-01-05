<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {
    Route::get('', [ProductController::class, 'index']);
    Route::post('', [ProductController::class, 'store']);
    Route::get('/{id}', [ProductController::class, 'show']);
    // Route::get('', [ProductController::class, 'index']);

    // Route::get('/status', [CourseController::class, 'getActiveAndInactives']);
    // Route::get('{course_id}', [CourseController::class, 'show']);
    // Route::post('', [CourseController::class, 'store']);
});


Route::prefix('users')->group(function () {
    Route::get('', [UserController::class, 'index']);
    Route::post('', [UserController::class, 'store']);
});


use App\Http\Controllers\Api\InvoiceController;


Route::prefix('invoices')->group(function () {
    Route::get('', [InvoiceController::class, 'index']);
    Route::post('', [InvoiceController::class, 'store']);
});
