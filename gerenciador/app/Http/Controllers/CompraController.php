<?php

namespace App\Http\Controllers;

use App\Mail\CompraRealizada;
use App\Models\Estoques;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use mysql_xdevapi\Table;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $estoques = Estoques::where('quantidade_atual', '>', 0)->get();
        $mensagemSucesso = $request->session()->get('mensagem.sucesso');
        return view('compras.index')->with('estoques', $estoques)->with('mensagemSucesso', $mensagemSucesso);
    }

    public function store(Request $request)
    {
        $request->validate([
            'quantidade' => ['required'],
        ]);
        $usuario = Auth::user()->id;
        $quantidade = $request->input('quantidade');
        $estoque_id = $request->input('estoque_id');
        $valor = 0;
        $hash = Str::random(35);
        $status = "Aguardando pagamento";

            for ($i = 0; $i < count($estoque_id); $i++) {

                    $valorProduto = DB::table('estoques')
                        ->where('id_produto_estoque', $estoque_id[$i])
                        ->value('valor_venda');
                    $valor = $valor + ($valorProduto * $quantidade[$i]);

            }

        $dados = [
            'id' => $usuario,
            'valor' => $valor,
            'status' => $status,
            'hash' => $hash
        ];
            $idInserido = DB::table('compras')->insertGetId($dados);

        //email
        /*$usuario = Auth::user();
        $email = new CompraRealizada($valor, $status);
        Mail::to($usuario->email)->queue($email);*/

            for ($i = 0; $i < count($estoque_id); $i++) {
                if ($quantidade[$i] > 0) {
                    $dados2 = [
                        'id_compra' => $idInserido,
                        'id_produto_estoque' => $estoque_id[$i],
                        'quantidade_compra' => $quantidade[$i],
                        'quantidade_restante' => $quantidade[$i]
                    ];
                    DB::table('compras_estoque')->insert($dados2);
                }
            }
        return redirect('/payment')->cookie('id', $idInserido)->cookie('valor', $valor)->cookie('hash', $hash);
    }
}
