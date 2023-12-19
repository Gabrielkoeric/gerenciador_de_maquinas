<?php

use App\Http\Controllers\AccessLogsController;
use App\Http\Controllers\CheckLogsController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\CompraIngressoController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\GeraController;
use App\Http\Controllers\IngressosController;
use App\Http\Controllers\LogsCheckController;
use App\Http\Controllers\LogsCheckoutController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\NomeacaoController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VendasController;
use App\Http\Middleware\Autenticador;
use App\Http\Middleware\ControleAcesso;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;


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
Route::resource('/gera', GeraController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//Route::put('/marcar-entregue/{id_compra}', 'GeraController@marcarEntregue')->name('marcar_entregue')->middleware(Autenticador::class)->middleware(ControleAcesso::class);;
//Route::put('/marcar-entregue/{id_compra}', [GeraController::class, 'marcarEntregue'])->name('marcar_entregue.update')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//usuarios
Route::resource('/usuario', UsuarioController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//Produtos
Route::resource('/estoque', EstoqueController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//compra
Route::resource('/compra', CompraController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//vendas
Route::get('/vendas/relatorio', [VendasController::class, 'relatorio'])->name('vendas.relatorio')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//Route::get('/vendas/relatorio/{compras}', [VendasController::class, 'relatorio'])->name('vendas.relatorio')->middleware(Autenticador::class);
Route::resource('/vendas', VendasController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//pedidos
Route::resource('/pedidos', PedidosController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//produtos
//Route::get('/produto/{hash}', [ProdutoController::class, 'processaRetirada'])/*->middleware(ControleAcesso::class)*/;
Route::post('/produtos/concluido', [ProdutoController::class, 'concluido'])->name('produtos.concluido')->middleware(ControleAcesso::class);
Route::get('/produto_entrega/{hash}', [ProdutoController::class, 'processaRetirada'])->name('produto_entrega.index')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::resource('produtos', ProdutoController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//ingressos
Route::resource('ingressos', IngressosController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//lote
Route::resource('lote', LoteController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//compra ingressos
Route::resource('compra_ingressos', CompraIngressoController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//nomeação de ingressos
Route::resource('nomeacao', NomeacaoController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//resultados
Route::get('/resultados/relatorioingresso', [ResultadosController::class, 'relatorioingresso'])->name('resultadosingresso.relatorio')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::get('/resultados/relatorioproduto', [ResultadosController::class, 'relatorioproduto'])->name('resultadosproduto.relatorio')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::get('/resultados/relatorioresultados', [ResultadosController::class, 'relatorioresultados'])->name('relatorioresultados.relatorio')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::resource('resultados', ResultadosController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//check-in e check-out
Route::post('/check/checkout', [CheckController::class, 'checkout'])->name('check.checkout')->middleware(ControleAcesso::class);
Route::post('/check/checkin', [CheckController::class, 'checkin'])->name('check.checkin')->middleware(ControleAcesso::class);
Route::get('/check_liberacao/{hash}', [CheckController::class, 'ingresso'])->name('check_liberacao.index')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::resource('check', CheckController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//logs access
Route::resource('access_logs', AccessLogsController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//logs check-in e check-out
Route::resource('logs_check', LogsCheckController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//perfis de usuarios
Route::resource('perfis_usuarios', PerfilController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//Gerar qrcode
Route::resource('qrcode', QRCodeController::class)/*->middleware(Autenticador::class)*/;
//Route::get('/qrcode', [QRCodeController::class, 'index'])->name('qrcode');
Route::get('/payment', [PagamentoController::class, 'createPayment'])->name('payment.create');
Route::get('/payment/success', [PagamentoController::class, 'secesso'])->name('payment.secesso');
Route::get('/payment/failure', [PagamentoController::class, 'flaha'])->name('payment.flaha');
Route::get('/payment/pending', [PagamentoController::class, 'pendente'])->name('payment.pendente');
Route::post('/webhook', [PagamentoController::class, 'handleWebhook']);



Route::get('/checkout', [MercadoPagoController::class, 'iniciarPagamento'])->name('checkout')->middleware(Autenticador::class);
//Route::post('/webhook', [MercadoPagoController::class, 'webhook'])->name('webhook');


//Route::get('login/google', "SocialiteController@redirectToProvider");
//Route::get('login/google/callback', 'SocialiteController@handleProviderCalback');
Route::get('login/google', [SocialiteController::class, 'redirectToProvider'])->name('login');
Route::get('login/google/callback', [SocialiteController::class, 'hendProviderCallback']);
Route::get('login/logout', [SocialiteController::class, 'destroy'])->name('logout');
Route::get('/forbidden', function () {return view('forbidden.index');});

Route::get('/email_novo_usuario', function (){return new \App\Mail\NovoUsuario();});
Route::get('/email_compra', function (){return new \App\Mail\CompraRealizada();});





