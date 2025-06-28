<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Routing\Middleware\SubstituteBindings;

use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductTypeController as AdminProductTypeController;
use App\Http\Controllers\Admin\VoucherController as AdminVoucherController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\AuthController;

use App\Models\User;

// Rate Limit
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('api', function ($request) {
    return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
});

/**
 * GLOBAL Route
 */
// Login
Route::post('/login', [AuthController::class, 'login']);

// Các route cần xác thực
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

// Public: anyone can view
Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']);
Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show']);
Route::get('/product-types', [App\Http\Controllers\ProductTypeController::class, 'index']);
Route::get('/banners', [\App\Http\Controllers\BannerController::class, 'index']);
/**
 * ADMIN
 *  */

// Get yourself info Test
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'is.admin']);

Route::prefix('admin')->middleware([
    EnsureFrontendRequestsAreStateful::class,
    'auth:sanctum',          // bắt buộc phải login
    'is.admin',              // chỉ cho admin
    'throttle:api',
    SubstituteBindings::class,
])->group(function () {
    Route::apiResource('products', AdminProductController::class);
    Route::apiResource('product-types', AdminProductTypeController::class);
    Route::apiResource('banners', AdminBannerController::class);
    Route::apiResource('vouchers', AdminVoucherController::class);
    Route::get('orders', [AdminOrderController::class, 'index']);

    // Users control
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::post('/users', [AdminUserController::class, 'store']);
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy']);
});

/**
 * USERS
 *  */
Route::middleware('auth:sanctum')->group(function () {

    // Cart
    Route::get('/cart', [CartItemController::class, 'index']);
    Route::post('/cart', [CartItemController::class, 'store']);
    Route::put('/cart/{id}', [CartItemController::class, 'update']); // nếu có
    Route::delete('/cart/{id}', [CartItemController::class, 'destroy']);

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);

    // Ratings
    Route::get('/ratings', [RatingController::class, 'index']);  // có thể mở public nếu muốn
    Route::post('/ratings', [RatingController::class, 'store']);
    Route::delete('/ratings/{id}', [RatingController::class, 'destroy']);

    // Profile
    Route::get('/me', [UserController::class, 'show']);
    Route::post('/me/password', [UserController::class, 'updatePassword']);
});
