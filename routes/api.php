<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TicketTypeController;
use App\Http\Controllers\API\Purchase\CartController;
use App\Http\Controllers\API\Purchase\CheckoutController;
use App\Http\Controllers\API\ClubhouseController;

use App\Http\Middleware\JWTMiddleware;

// AUTHENTICATION ROUTES
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// API FOR CRUD ROLES
Route::middleware([JWTMiddleware::class])->group(function () {
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
});

// API FOR CRUD TICKET TYPE
Route::middleware([JWTMiddleware::class])->group( function () {
   Route::get('/ticketType', [TicketTypeController::class, 'index']);
   Route::post('/ticketType', [TicketTypeController::class, 'store']); 
   Route::get('/ticketType/{id}', [TicketTypeController::class, 'show']);
   Route::put('/ticketType/{id}', [TicketTypeController::class, 'update']);
   Route::delete('/ticketType/{id}', [TicketTypeController::class, 'destroy']);
});

// API FOR PURCHASE
Route::middleware([JWTMiddleware::class])->group( function () {
    // API FOR CART
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::get('/cart/{purchaseId}', [CartController::class, 'getCartData']);

    // API FOR CHECKOUT + PAYMENT + CREATE TICKET ENTRIES
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
});

// API FOR CLUBHOUSE
Route::middleware([JWTMiddleware::class])->group( function () {
    Route::get('/clubhouse', [ClubhouseController::class, 'index']);
    Route::post('/clubhouse', [ClubhouseController::class, 'store']);
    Route::get('/clubhouse/{id}', [ClubhouseController::class, 'show']);
    Route::put('/clubhouse/{id}', [ClubhouseController::class, 'update']);
    Route::delete('/clubhouse/{id}', [ClubhouseController::class, 'destroy']);
});
