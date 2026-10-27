<?php

namespace App\Http\Controllers\Autenticacao2FA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Autenticacao2FA\Autenticacao2FAService;
use App\Repositories\Autenticacao2FA\Autenticacao2FARepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Autenticacao2FAController extends Controller
{
    protected $autenticacao2FAService;
    protected $repository;

    public function __construct(
        Autenticacao2FAService $autenticacao2FAService,
        Autenticacao2FARepository $repository
    ) {
        $this->autenticacao2FAService = $autenticacao2FAService;
        $this->repository = $repository;
    }

    public function iniciar(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login_local');
        }

        if (session('2fa_validado') === true) {
            return redirect()->route('home.index');
        }

        $this->autenticacao2FAService->iniciar(auth()->user());

        return redirect()->route('2fa.index');
    }

        public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login_local');
        }

        if (session('2fa_validado') === true) {
            return redirect()->route('home.index');
        }

        $idDesafio = session('2fa_desafio_id');

        if (!$idDesafio) {
            return redirect()->route('2fa.iniciar');
        }

        $desafio = $this->repository->buscarPorId($idDesafio);

        if (
            !$desafio ||
            $desafio->id_usuario != auth()->id() ||
            $desafio->validado_em !== null ||
            now()->gte(\Illuminate\Support\Carbon::parse($desafio->expira_em))
        ) {
            if ($desafio) {
                $this->repository->invalidar($idDesafio);
            }

            session()->forget('2fa_desafio_id');

            return redirect()
                ->route('2fa.iniciar')
                ->with('mensagemErro', 'O código expirou. Enviamos um novo código.');
        }

        if ((int) $desafio->tentativas >= 5) {
            Auth::logout();
            $request = request();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login_local')
                ->with('mensagemErro', 'Limite de tentativas excedido. Faça login novamente.');
        }

        return view('autenticacao2fa.index');
    }

    public function validar(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login_local');
        }

        if (session('2fa_validado') === true) {
            return redirect()->route('home.index');
        }

        $request->validate([
            'codigo' => ['required', 'string', 'regex:/^[0-9]{8}$/'],
        ], [
            'codigo.required' => 'Informe o código recebido por e-mail.',
            'codigo.regex' => 'O código deve conter exatamente 8 números.',
        ]);

        $idDesafio = session('2fa_desafio_id');

        if (!$idDesafio) {
            return redirect()->route('2fa.iniciar');
        }

        $desafio = $this->repository->buscarPorId($idDesafio);

        if (
            !$desafio ||
            $desafio->id_usuario != auth()->id() ||
            $desafio->validado_em !== null
        ) {
            session()->forget('2fa_desafio_id');

            return redirect()
                ->route('2fa.iniciar')
                ->with('mensagemErro', 'Desafio inválido. Um novo código será enviado.');
        }

        if (now()->gte(\Illuminate\Support\Carbon::parse($desafio->expira_em))) {
            $this->repository->invalidar($idDesafio);
            session()->forget('2fa_desafio_id');

            return redirect()
                ->route('2fa.iniciar')
                ->with('mensagemErro', 'O código expirou. Um novo código será enviado.');
        }

        if ((int) $desafio->tentativas >= 5) {
            $this->repository->invalidar($idDesafio);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login_local')
                ->with('mensagemErro', 'Limite de tentativas excedido. Faça login novamente.');
        }

        if (!Hash::check($request->input('codigo'), $desafio->codigo_hash)) {
            $this->repository->incrementarTentativas($idDesafio);

            if ((int) $desafio->tentativas + 1 >= 5) {
                $this->repository->invalidar($idDesafio);

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login_local')
                    ->with('mensagemErro', 'Limite de tentativas excedido. Faça login novamente.');
            }

            return back()
                ->withErrors(['codigo' => 'Código incorreto. Confira o e-mail e tente novamente.'])
                ->withInput();
        }

        $this->repository->marcarComoValidado($idDesafio);

        session([
            '2fa_validado' => true,
        ]);

        session()->forget('2fa_desafio_id');

        return redirect()
            ->route('home.index')
            ->with('mensagemSucesso', 'Autenticação em dois fatores concluída.');
    }
}
