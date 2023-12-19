<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MercadoPago\Item;
use MercadoPago\Payer;
use MercadoPago\Preference;
use MercadoPago\SDK;
use MercadoPago\Payment;


class PagamentoController extends Controller
{
    public function createPayment(Request $request)
    {
        //Log::info('vc esta no pagamento');
        /*$id = $request->session()->get('pagamnto')['id'];
        $valor = $request->session()->get('pagamnto')['valor'];
        $hash = $request->session()->get('hash')['hash'];*/
        $pagamento = session('pagamento');

        $id = $request->cookie('id');
        $valor = $request->cookie('valor');
        $hash = $request->cookie('hash');
        /*$nome = Auth::user()->name;
        $email = Auth::user()->email;*/
        $accessToken = config('services.mercado_pago.access_token');
        // Configure suas credenciais do MercadoPago
        \MercadoPago\SDK::setAccessToken("$accessToken");

        // Crie um item de compra
        $item = new Item();
        $item->id = "$id";
        $item->title = "Compra #$id";
        $item->quantity = 1;
        $item->currency_id = 'BRL'; // Moeda em Reais
        $item->unit_price = $valor; // Preço do produto


       // Crie um comprador (payer)
        $payer = new Payer();
        $payer->name = "Capela Nossa Senhora das Graças";
        $payer->email = "capela.serragrande@gmail.com";
        /*$payer->name = "$nome";
        $payer->email = $email;*/

        // Configurações de pagamento
        $payment_methods = [
            'excluded_payment_methods' => [
                ['id' => 'bolbradesco'],
                ['id' => 'pec'],
            ],
            'excluded_payment_types' => [
                ['id' => 'credit_card'],
                ['id' => 'debit_card'],
                ['id' => 'prepaid_card'],
                // Adicione outras formas de pagamento que você deseja excluir
            ],
            'installments' => 1,
        ];


        // Crie uma preferência de pagamento
        $preference = new Preference();
        $preference->items = [$item];
        $preference->payer = $payer;
        $preference->external_reference = $hash;
        $preference->back_urls = [
            'success' => route('gera.index'), // Rota de sucesso
            'failure' => route('gera.index'), // Rota de falha
            'pending' => route('gera.index'), // Rota pendente
        ];
        $preference->auto_return = 'approved'; // Redirecionamento automático após pagamento aprovado
        $preference->payment_methods = $payment_methods;

        $additional_info = array(
            'name' => 'Capela Nossa Senhora das Graças',
            'email' => 'capela.serragrande@gmail.com',
            'cnpj' => '12345678901234', // Substitua pelo CNPJ real
        );

        $preference->additional_info = json_encode($additional_info);

        // Salve a preferência e obtenha a URL de pagamento
        $preference->save();
        $paymentUrl = $preference->init_point;

        DB::table('compras')
            ->where('hash', $hash)
            ->update([
                'link_pagamento' => $paymentUrl,
            ]);
        // Redirecione o usuário para a página de pagamento do MercadoPago
        //return redirect($paymentUrl);
        return redirect()->to($paymentUrl);

    }

    public function secesso(Request $request){
/*
        $collectionStatus = $request->input('collection_status');
        $status = $request->input('status');
        $externalReference = $request->input('external_reference');
        $usuario = Auth::user()->id;

        DB::table('compras')
            ->where('hash', $externalReference) // Substitua $idDaCompra pelo ID da compra que você deseja atualizar
            ->update(['status' => $status]);

            $resultados = DB::table('compras_estoque')
            ->join('compras', 'compras_estoque.id_compra', '=', 'compras.id_compra')
            ->where('compras.hash', $externalReference)
            ->select('compras_estoque.*')
            ->get();

        foreach ($resultados as $resultado) {
            DB::table('produtos_disponiveis')->updateOrInsert(
                [
                    'id' => $usuario,
                    'id_produto_estoque' => $resultado->id_produto_estoque,
                ],
                ['quantidade' => DB::raw('quantidade + ' . $resultado->quantidade_compra)]
            );
        }*/
        return to_route('gera.index');
    }
    public function flaha(){
        //dd("flaha");
        return to_route('gera.index');
    }
    public function pendente(){
        //dd("pendente");
        return to_route('gera.index');
    }

    public function handleWebhook(Request $request)
    {
        // Configurar as credenciais do Mercado Pago
        $accessToken = config('services.mercado_pago.access_token');
        SDK::setAccessToken($accessToken);

        //consulta na api
        // Consultar o status da compra
        $paymentId = $request->input('data.id');
        $payment = Payment::find_by_id($paymentId);
/*
        //pegar o id interno
        // Acessar os detalhes do item
        $items = $payment->additional_info->items;
// Verificar se existem itens e obter o ID
        if (!empty($items)) {
            $itemId = $items[0]->id;
            //Log::info('ID do item: ' . $itemId);
        }*/
        $externalReference = $payment->external_reference;
        $status = $payment->status;

        // Adicionar 'external_reference' ao log
       // Log::info('External Reference: ' . $externalReference);
        DB::table('compras')
            ->where('hash', $externalReference) // Substitua $idDaCompra pelo ID da compra que você deseja atualizar
            ->update(['status' => $status]);

        return response()->json(['status' => 'OK'], 200);

    }

}
