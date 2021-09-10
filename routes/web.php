<?php

use Inertia\Inertia;
use App\Models\Domain;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
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

Route::get('/', [DashboardController::class, 'welcome'])->name('welcome');

Route::get('/fish', function () {
    function domainsGenerator()
    {
        foreach (Domain::cursor() as $domain) {
            yield $domain;
        }
    }

    FastExcel::data(domainsGenerator())->export('file.csv', function ($domain) {
        return [
            '網域名稱' => $domain->name,
            '域名到期時間' => $domain->domain_expired_at->format('Y/m/d'),
            '憑證到期時間' => $domain->certificate_expired_at ? $domain->certificate_expired_at->format('Y/m/d') : '',
            '產品' => $domain->product,
            '提交者' => $domain->submit,
            'DNS' => $domain->dns,
            '名稱伺服器' => implode(',', $domain->nameservers),
            '域名商' => $domain->vendor,
            '備註' => $domain->remark,
        ];
    });

    return Inertia::render('Dashboard');
});

Route::get('/auth/redirect', [AuthController::class, 'redirect'])->name('login.google');
Route::get('/auth/callback', [AuthController::class, 'callback']);

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');

    Route::get('/webhooks', [WebhookReceiverController::class, 'show'])->name('webhooks');
    Route::get('/webhooks/create', [WebhookReceiverController::class, 'create'])->name('webhooks.create');
    Route::get('/webhooks/edit', [WebhookReceiverController::class, 'edit'])->name('webhooks.edit');
    Route::put('/webhooks/{webhookReceiver}', [WebhookReceiverController::class, 'update'])->name('webhooks.update');
    Route::delete('/webhooks/{webhookReceiver}', [WebhookReceiverController::class, 'destroy'])->name('webhooks.destroy');

    Route::get('/domains', [DomainValidController::class, 'index'])->name('domains.index');
    Route::post('/domains', [DomainValidController::class, 'store'])->name('domains.store');
    Route::delete('/domains', [DomainValidController::class, 'destroy'])->name('domains.destroy');

    Route::get('/admin', [AdminController::class, 'show'])->name('admin');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');

    Route::get('/roles/admin', [RoleAdminController::class, 'index'])->name('roles.admin.index');
});
