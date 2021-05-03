<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebhookReceiverController;

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

    Route::get('/webhooks', [WebhookReceiverController::class, 'show'])->name('webhooks');
    Route::get('/webhooks/create', [WebhookReceiverController::class, 'create'])->name('webhooks.create');
    Route::get('/webhooks/edit', [WebhookReceiverController::class, 'edit'])->name('webhooks.edit');
    Route::get('/webhooks/wait', [WebhookReceiverController::class, 'wait'])->name('webhooks.wait');
    Route::post('/webhooks', [WebhookReceiverController::class, 'link'])->name('webhooks.store');
    Route::put('/webhooks/{webhookReceiver}', [WebhookReceiverController::class, 'update'])->name('webhooks.update');
    Route::post('/webhooks/relink', [WebhookReceiverController::class, 'relink'])->name('webhooks.relink');
    Route::delete('/webhooks/{webhookReceiver}', [WebhookReceiverController::class, 'destroy'])->name('webhooks.destroy');
});
