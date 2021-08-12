<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleAdminController;
use App\Http\Controllers\DomainValidController;
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
    Route::post('/webhooks', [WebhookReceiverController::class, 'store'])->name('webhooks.store');
    Route::get('/webhooks/edit', [WebhookReceiverController::class, 'edit'])->name('webhooks.edit');
    Route::put('/webhooks/{webhookReceiver}', [WebhookReceiverController::class, 'update'])->name('webhooks.update');
    Route::post('/webhooks/relink', [WebhookReceiverController::class, 'relink'])->name('webhooks.relink');
    Route::delete('/webhooks/{webhookReceiver}', [WebhookReceiverController::class, 'destroy'])->name('webhooks.destroy');

    Route::get('/domains', [DomainValidController::class, 'show'])->name('domains');
    Route::post('/domains', [DomainValidController::class, 'store'])->name('domains.store');
    Route::post('/domains/link', [DomainValidController::class, 'link'])->name('domains.link');
    Route::delete('/domains', [DomainValidController::class, 'destroy'])->name('domains.destroy');

    Route::get('/admin', [AdminController::class, 'show'])->name('admin');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');

    Route::get('/roles/admin', [RoleAdminController::class, 'index'])->name('roles.admin.index');
});
