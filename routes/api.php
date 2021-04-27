<?php

use App\Http\Controllers\Api\TelegramBotController;
use App\Http\Controllers\Api\Webhook\ForwardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/botLink', [TelegramBotController::class, 'link']);
});

Route::post('/webhook/telegram', [TelegramBotController::class, 'callback']);

Route::post('/webhook', [ForwardController::class, 'receive']);

