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

class LocalAuthController extends Controller
{
    /**
     * Exibe o formulário de login.
     */
    public function showLoginForm()
    {
        return view('usuarios.login');
    }

    /**
     * Processa a autenticação do usuário.
     */
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

    Auth::login($usuario, $request->filled('remember'));

    AccessLog::create([
        'id' => $usuario->id,
        'ip_address' => request()->ip(),
    ]);


    return redirect()->route('home.index');
}

    /**
     * Faz logout do usuário.
     */
    public function logout()
    {
        Auth::logout();
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

    //event(new Registered($usuario));

    //return response()->json(['message' => 'Usuário cadastrado! Verifique seu e-mail.']);

    AccessLog::create([
        'id' => $usuario->id,
        'ip_address' => request()->ip(),
    ]);

    Auth::login($usuario, true); // O `true` ativa o remember_token automaticamente

    return redirect()->route('home.index');
}

}
