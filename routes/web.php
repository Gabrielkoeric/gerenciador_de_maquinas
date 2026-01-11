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
use App\Http\Controllers\ServerFisicoController;
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
use App\Http\Controllers\Api\ApiIp5Controller;
use App\Http\Controllers\Api\ApiIp6Controller;
use App\Http\Controllers\Api\ApiIp7Controller;
use App\Http\Controllers\Api\ConfereBackupController;
use App\Http\Controllers\Auth\LocalAuthController;
use App\Http\Controllers\VmController;
use App\Http\Controllers\VmServicoController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\ComandoController;
use App\Http\Controllers\RotaLogsController;
use App\Http\Controllers\LogsExecucoesController;
use App\Http\Controllers\ExecutaComandoController;
use App\Http\Controllers\AsyncTasksController;
use App\Http\Controllers\ConfigGeralController;
use App\Http\Controllers\SecaoCloudController;
use App\Http\Controllers\RedeController;
use App\Http\Controllers\IpLanController;
use App\Http\Controllers\DocumentacaoController;
use App\Http\Controllers\DominioController;
use App\Http\Controllers\DeployController;
use App\Http\Controllers\BancodedadosController;
use App\Http\Controllers\RcloneExecucoesController;
use App\Http\Controllers\RcloneLogsExecucoesController;
use App\Http\Controllers\AcessosController;
use App\Http\Controllers\ResumoController;
use App\Http\Controllers\AgendamentosController;
use App\Http\Controllers\HorariosAgendamentosController;
use App\Http\Controllers\ExecucaoGeralController;
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
//Route::get('/home', [HomeController::class, 'index'])->name('home.index')->middleware(Autenticador::class);
//Route::get('/home', [HomeController::class, 'index'])->name('home.index')->middleware(['auth', 'verified', Autenticador::class]);
Route::get('/home', [HomeController::class, 'index'])->name('home.index')->middleware([Autenticador::class]);

//gera
//usuarios
Route::resource('/usuario', UsuarioController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//logs access
Route::resource('access_logs', AccessLogsController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//perfis de usuarios
Route::resource('perfis_usuarios', PerfilController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//busca de ip's
Route::resource('ip', IpController::class)/*->middleware(Autenticador::class)->middleware(ControleAcesso::class)*/;
//ip publicos da unidade
Route::resource('ip_publico', IpPublicoController::class)/*->middleware(Autenticador::class)->middleware(ControleAcesso::class)*/;
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
//Executar
Route::resource('logs_execucoes', LogsExecucoesController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//Route::get('login/google', "SocialiteController@redirectToProvider");
//Route::get('login/google/callback', 'SocialiteController@handleProviderCalback');
Route::get('login/google', [SocialiteController::class, 'redirectToProvider'])->name('login');
Route::get('login/google/callback', [SocialiteController::class, 'hendProviderCallback']);
Route::get('login/logout', [SocialiteController::class, 'destroy'])->name('logout_google');

//login local
Route::get('login_local', [LocalAuthController::class, 'showLoginForm'])->name('login_local');
Route::post('login_local', [LocalAuthController::class, 'login'])->name('login_local.post');
Route::post('logout', [LocalAuthController::class, 'logout'])->name('logout');
//registro usuario local
Route::get('/register', [LocalAuthController::class, 'showRegisterForm'])->name('register_local');
Route::post('/register', [LocalAuthController::class, 'register'])->name('register_local.post');


Route::get('/forbidden', function () {return view('forbidden.index');});

Route::get('/email_novo_usuario', function (){return new \App\Mail\NovoUsuario();});
Route::get('/email_compra', function (){return new \App\Mail\CompraRealizada();});

//usuarios
// Defina a rota em web.php
Route::post('/server/executar', [ExecutaComandoController::class, 'manipulaHostFisico'])->name('server.executar')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::post('/server/executar-comando', [ExecutaComandoController::class, 'executarComando'])->name('server.executarComando')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::resource('/server', ServerFisicoController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//ssh

Route::get('/ssh/{id}', [SSHController::class, 'ssh'])->name('conecta.ssh')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//Route::resource('/server', ServerController::class);
Route::post('/ssh/{id}', [SSHController::class, 'ssh'])->name('conecta.ssh')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//vm
Route::post('/vm/executar', [ExecutaComandoController::class, 'manipulaVm'])->name('vm.executar')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::resource('/vm', VmController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//vm servico
Route::get('/vm_servico_publico', [VmServicoController::class, 'acessopublico'])->name('vm_servico.publico');
Route::post('/vm_servico/executar', [ExecutaComandoController::class, 'manipulaServico'])->name('vm_servico.executar')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::resource('/vm_servico', VmServicoController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//Route::post('/vm_servico/executar', [VmServicoController::class, 'executarAcao'])->name('vmservico.executarAcao')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//servico
Route::resource('/servico', ServicoController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//cliente_escala
Route::get('/cliente_escala/controle', [ClienteEscalaController::class, 'controle'])->name('cliente_escala.controle')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::post('/cliente_escala/{id}/update-quantidade', [ClienteEscalaController::class, 'updateQuantidade'])->name('cliente_escala.updateQuantidade')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::get('/cliente_escala/gerardm', [ClienteEscalaController::class, 'gerardm'])->name('cliente_escala.gerardm')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::get('/cliente_escala/buscar', [ClienteEscalaController::class, 'buscarClientes'])->name('cliente_escala.buscar')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::resource('/cliente', ClienteController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//rota logs
Route::resource('/rota_logs', RotaLogsController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//comandos
Route::resource('/comando', ComandoController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//assync task
Route::resource('/asynctasks', AsyncTasksController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//config geral
Route::resource('/config_geral', ConfigGeralController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//seção cloud
Route::post('/secao_cloud/{id}/resetar', [SecaoCloudController::class, 'resetar'])->name('secao_cloud.resetar')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::resource('/secao_cloud', SecaoCloudController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//rede
Route::resource('/redes', RedeController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);
//iplan
Route::resource('/ip_lan', IpLanController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//iplan
Route::resource('/documentacao', DocumentacaoController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//iplan
Route::resource('/dominios', DominioController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//deploy
Route::post('/deploy/ws', [DeployController::class, 'ws'])->name('deploy.ws')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::post('/deploy/swarm', [DeployController::class, 'swarm'])->name('deploy.swarm')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::post('/deploy/server', [DeployController::class, 'server'])->name('deploy.server')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::resource('/deploy', DeployController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//banco de dados
Route::resource('/bancodedados', BancodedadosController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//rclone execucoes
Route::get('/rclone/executa', [RcloneExecucoesController::class, 'executa'])->name('rclone.executa')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::resource('/rclone', RcloneExecucoesController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//logs rclone execuções
Route::resource('/rclonelogs', RcloneLogsExecucoesController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//acessos
Route::resource('/acessos', AcessosController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//acessos
Route::resource('/resumo', ResumoController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//agendamentos
Route::post('/agendamentos/{id}/executar', [AgendamentosController::class, 'executar'])->name('agendamentos.executar')->middleware(Autenticador::class)->middleware(ControleAcesso::class);
Route::resource('/agendamentos', AgendamentosController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//horarios agendamentos
Route::resource('/horarios_agendamentos', HorariosAgendamentosController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//horarios agendamentos
Route::resource('/execucao_geral', ExecucaoGeralController::class)->middleware(Autenticador::class)->middleware(ControleAcesso::class);

//api
Route::post('/api/ip', [ApiIpController::class, 'store']);

//api2
Route::post('/api/ip2', [ApiIp2Controller::class, 'store']);

//api3
Route::post('/api/ip3', [ApiIp3Controller::class, 'store']);

//api4
Route::post('/api/ip4', [ApiIp4Controller::class, 'store']);
//api5
Route::post('/api/ip5', [ApiIp5Controller::class, 'store']);
//api6 salva os ip na tabela temp e processa depois
Route::post('/api/ip6', [ApiIp6Controller::class, 'store']);
//api7 salva os ip na tabela temp e processa depois mas disparando as async primeiro
Route::post('/api/ip7', [ApiIp7Controller::class, 'store']);
//confere backup
Route::post('/api/backup', [ConfereBackupController::class, 'store']);