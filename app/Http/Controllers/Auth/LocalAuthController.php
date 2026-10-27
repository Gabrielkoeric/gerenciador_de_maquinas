<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Models\AccessLog;
use App\Repositories\Usuario\UsuarioRepository;

class LocalAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('usuarios.login');
    }
/*
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Verifica se o usuário existe
        $usuario = User::where('email', $credentials['email'])->first();

        if (!$usuario) {
            // Redireciona para o formulário de cadastro se o e-mail não existir
            return redirect()->route('register_local')->withInput();
        }

        // Verifica a senha
        if (!Hash::check($credentials['password'], $usuario->password)) {
            return back()->withErrors(['email' => 'Credenciais inválidas.'])->withInput();
        }

        //Auth::login($usuario, $request->filled('remember')); server para salvar e validar o token lembrar me
        Auth::login($usuario);

        AccessLog::create([
            'id' => $usuario->id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('home.index');
    }
*/

    public function login(Request $request, UsuarioRepository $usuarioRepository)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Busca o usuário pelo repository
        $usuario = $usuarioRepository->buscarPorEmail($credentials['email']);

        if (!$usuario) {
            if (config('app.env') == 'interno') {
                return redirect()->route('register_local')->withInput();
            }

            return back()
                ->withErrors(['email' => 'Email Não Cadastrado Contate o Administrador da Escalasoft'])
                ->withInput();
        }

        // Verifica se o usuário está ativo
        if (!$usuario->ativo) {
            return back()
                ->withErrors([
                    'email' => 'Usuario Bloqueado Contate o Administrador da Escalasoft.'
                ])
                ->withInput();
        }

        // Verifica a senha
        if (!Hash::check($credentials['password'], $usuario->password)) {

            // Incrementa as tentativas pelo repository
            $tentativas = $usuarioRepository->incrementarTentativasLogin(
                (int) $usuario->id
            );

            // Bloqueia a conta ao atingir 3 tentativas
            if ($tentativas >= 3) {
                $usuarioRepository->bloquearSeAtingirLimite(
                    (int) $usuario->id
                );

                return back()
                    ->withErrors([
                        'email' => 'Usuário bloqueado por excesso de tentativas.'
                    ])
                    ->withInput();
            }

            return back()
                ->withErrors(['email' => 'Credenciais inválidas.'])
                ->withInput();
        }

        // Zera as tentativas após senha correta
        if ($usuario->tentativas_login > 0) {
            $usuarioRepository->zerarTentativasLogin((int) $usuario->id);
        }

        // Recupera o Model para autenticação Laravel
        $usuarioModel = User::find($usuario->id);

        Auth::login($usuarioModel);

        AccessLog::create([
            'id' => $usuario->id,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('home.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login_local');
    }

    public function showRegisterForm()
    {
        return view('usuarios.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:usuarios,email',
            'nome_completo' => 'required|string|max:255',
            'celular' => 'nullable|string|max:13',
            'password' => 'required|min:6|confirmed',
        ]);

        $usuario = User::create([
            'email' => $request->email,
            'nome_completo' => $request->nome_completo,
            'celular' => $request->celular,
            'password' => Hash::make($request->password),
        ]);

        AccessLog::create([
            'id' => $usuario->id,
            'ip_address' => request()->ip(),
        ]);

        Auth::login($usuario);

        return redirect()->route('home.index');
    }
}
