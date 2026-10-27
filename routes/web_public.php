<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\Autenticador;
use App\Http\Middleware\ControleAcesso;
use App\Http\Middleware\ValidarHorarioPlantao;
use App\Http\Middleware\Verificar2FA;
use App\Http\Controllers\Auth\LocalAuthController;
use App\Http\Controllers\Bkp\bkpController;
use App\Http\Controllers\Autenticacao2FA\Autenticacao2FAController;

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
Route::get('/', [HomeController::class, 'index'])->name('home.index')->middleware(ValidarHorarioPlantao::class)->middleware(Verificar2FA::class);
Route::get('/home', [HomeController::class, 'index'])->name('home.index')->middleware(ValidarHorarioPlantao::class)->middleware(Verificar2FA::class);

//login local
Route::get('login_local', [LocalAuthController::class, 'showLoginForm'])->name('login_local');
Route::post('login_local', [LocalAuthController::class, 'login'])->name('login_local.post');
Route::post('logout', [LocalAuthController::class, 'logout'])->name('logout');

//bkp
Route::get('/bkp/download/{id}', [bkpController::class, 'download'])->name('bkp.download')->middleware(Verificar2FA::class)->middleware(Autenticador::class)->middleware(ValidarHorarioPlantao::class)->middleware(ControleAcesso::class);
Route::get('/bkp', [bkpController::class, 'index'])->name('bkp.index')->middleware(Verificar2FA::class)->middleware(Autenticador::class)->middleware(ValidarHorarioPlantao::class)->middleware(ControleAcesso::class);
//
Route::post('/2fa/validar', [Autenticacao2FAController::class, 'validar'])->name('2fa.validar')->middleware(Verificar2FA::class)->middleware(Autenticador::class)->middleware(ValidarHorarioPlantao::class)->middleware(ControleAcesso::class);
Route::get('/2fa/iniciar', [Autenticacao2FAController::class, 'iniciar'])->name('2fa.iniciar')->middleware(Verificar2FA::class)->middleware(Autenticador::class)->middleware(ValidarHorarioPlantao::class)->middleware(ControleAcesso::class);
Route::get('/2fa', [Autenticacao2FAController::class, 'index'])->name('2fa.index')->middleware(Verificar2FA::class)->middleware(Autenticador::class)->middleware(ValidarHorarioPlantao::class)->middleware(ControleAcesso::class);