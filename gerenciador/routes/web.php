<?php

use App\Http\Controllers\AccessLogsController;
use App\Http\Controllers\CheckLogsController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\CompraIngressoController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\CpuController;
use App\Http\Controllers\ScriptController;
use App\Http\Controllers\ExecutarController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\GeraController;
use App\Http\Controllers\HdController;
use App\Http\Controllers\IngressosController;
use App\Http\Controllers\IpPublicoController;
use App\Http\Controllers\LogsCheckController;
use App\Http\Controllers\LogsCheckoutController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\MemoriaController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\NomeacaoController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\SSHController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\IpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VendasController;
use App\Http\Middleware\Autenticador;
use App\Http\Middleware\ControleAcesso;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\Api\ApiIpController;
use App\Http\Controllers\Api\ApiIp2Controller;
use App\Http\Controllers\Api\ApiIp3Controller;
use App\Http\Controllers\Api\ApiIp4Controller;


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

//home
Route::get('/', [HomeController::class, 'index'])->name('home.index')->middleware(Autenticador::class);
Route::get('/home', [HomeController::class, 'index'])->name('home.index')->middleware(Autenticador::class);
//gera
//usuarios
Route::resource('/usuario', UsuarioController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//logs access
Route::resource('access_logs', AccessLogsController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//perfis de usuarios
Route::resource('perfis_usuarios', PerfilController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//busca de ip's
Route::resource('ip', IpController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//ip publicos da unidade
Route::resource('ip_publico', IpPublicoController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//hd
Route::resource('hd', HdController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//memoria
Route::resource('memoria', MemoriaController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//CPU
Route::resource('cpu', CpuController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//Script
Route::resource('script', ScriptController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//Executar
Route::resource('executar', ExecutarController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//Route::get('login/google', "SocialiteController@redirectToProvider");
//Route::get('login/google/callback', 'SocialiteController@handleProviderCalback');
Route::get('login/google', [SocialiteController::class, 'redirectToProvider'])->name('login');
Route::get('login/google/callback', [SocialiteController::class, 'hendProviderCallback']);
Route::get('login/logout', [SocialiteController::class, 'destroy'])->name('logout');
Route::get('/forbidden', function () {return view('forbidden.index');});

Route::get('/email_novo_usuario', function (){return new \App\Mail\NovoUsuario();});
Route::get('/email_compra', function (){return new \App\Mail\CompraRealizada();});

//usuarios
Route::resource('/server', ServerController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//ssh

Route::get('/ssh/{id}', [SSHController::class, 'ssh'])->name('server.ssh')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//Route::resource('/server', ServerController::class);
Route::post('/ssh/{id}', [SSHController::class, 'ssh'])->name('server.ssh')->middleware(Autenticador::class)->middleware(ControleAcesso::class);



//api
Route::post('/api/ip', [ApiIpController::class, 'store']);

//api2
Route::post('/api/ip2', [ApiIp2Controller::class, 'store']);

//api3
Route::post('/api/ip3', [ApiIp3Controller::class, 'store']);

//api4
Route::post('/api/ip4', [ApiIp4Controller::class, 'store']);






