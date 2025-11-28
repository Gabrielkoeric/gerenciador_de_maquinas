<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoXmlController;
use App\Http\Controllers\ApiServicoController;
use App\Http\Controllers\StatusServicoController;
use App\Http\Controllers\SecaoCloudController;
use App\Http\Controllers\RcloneLogsExecucoesController;
use App\Http\Middleware\Autenticador;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//cor api

Route::get('/cor', [\App\Http\Controllers\Api\CorController::class, 'index']);


Route::post('/xml_eventos', [EventoXmlController::class, 'receber']);
Route::post('/servicos', [ApiServicoController::class, 'receber']);


Route::post('/status_servico', [StatusServicoController::class, 'store']);
//Route::middleware([])->post('/status_servico', [StatusServicoController::class, 'store']);

Route::post('/secao', [SecaoCloudController::class, 'api']);

//Route::post('/status_servico', [StatusServicoController::class, 'store'])->withoutMiddleware([Autenticador::class]);

Route::get('/rclone', [RcloneLogsExecucoesController::class, 'api']);
Route::get('/usuarios_logados', [SecaoCloudController::class, 'usuarios_logados']);