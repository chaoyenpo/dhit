<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebhookRecevierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', [DashboardController::class, 'welcome']);

Route::get('/auth/redirect', [AuthController::class, 'redirect'])->name('login.google');
Route::get('/auth/callback', [AuthController::class, 'callback']);

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::get('/webhooks', [WebhookRecevierController::class, 'show'])->name('webhooks');
    Route::get('/webhooks/create', [WebhookRecevierController::class, 'create'])->name('webhooks.create');
    Route::get('/webhooks/edit', [WebhookRecevierController::class, 'edit'])->name('webhooks.edit');
});
