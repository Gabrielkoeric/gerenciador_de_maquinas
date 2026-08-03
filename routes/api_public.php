<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Middleware\ApiLogger;

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

Route::get('escalacloudrunner/{chave}', [ClienteController::class, 'escalaCloudLauncher'])->middleware(ApiLogger::class);
Route::get('escalacloudlauncher/{chave}', [ClienteController::class, 'escalaCloudLauncher'])->middleware(ApiLogger::class);