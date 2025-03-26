<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TicketTypeController;
use App\Http\Controllers\API\Purchase\CartController;
use App\Http\Controllers\API\Purchase\CheckoutController;
use App\Http\Controllers\API\ClubhouseController;
use App\Http\Controllers\API\Item\ItemCategoryController;
use App\Http\Controllers\API\Item\ItemController;
use App\Http\Controllers\API\Item\ManagementItemController;
use App\Http\Controllers\API\Package\PackageCategoryController;

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

// API FOR ITEM 
Route::middleware([JWTMiddleware::class])->group( function () {
    // API FOR CRUD ITEM CATEGORY
    Route::get('/itemCategory', [ItemCategoryController::class, 'index']);
    Route::post('/itemCategory', [ItemCategoryController::class, 'store']);
    Route::get('/itemCategory/{id}', [ItemCategoryController::class, 'show']);
    Route::put('/itemCategory/{id}', [ItemCategoryController::class, 'update']);
    Route::delete('/itemCategory/{id}', [ItemCategoryController::class, 'destroy']);

    // API FOR CRUD ITEM
    Route::get('/item', [ItemController::class, 'index']);
    Route::post('/item', [ItemController::class, 'store']);
    Route::get('/item/{id}', [ItemController::class, 'show']);
    Route::put('/item/{id}', [ItemController::class, 'update']);
    Route::delete('/item/{id}', [ItemController::class, 'destroy']);

    // API FOR UPDATE QTY ITEM
    Route::post('/updateQtyItem/{id}', [ManagementItemController::class, 'updateQtyItem']);

    // API FOR GET ITEM LOG DATA
    Route::get('/showItemLog', [ManagementItemController::class, 'showAllItemLog']);
    Route::get('/showItemLog/by-log-id/{id}', [ManagementItemController::class, 'showItemLog']);
    Route::get('/showItemLog/by-item-log/{id}', [ManagementItemController::class, 'showItemByItemID']);
});

// API FOR PACKAGE
Route::middleware([JWTMiddleware::class])->group( function () {
    // API FOR CRUD PACKAGE CATEGORY
    Route::get('/packageCategory', [PackageCategoryController::class, 'index']);
    Route::post('/packageCategory', [PackageCategoryController::class, 'store']);
    Route::get('/packageCategory/{id}', [PackageCategoryController::class, 'show']);
    Route::put('/packageCategory/{id}', [PackageCategoryController::class, 'update']);
    Route::delete('/packageCategory/{id}', [PackageCategoryController::class, 'destroy']);
});