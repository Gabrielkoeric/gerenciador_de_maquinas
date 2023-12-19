<x-layout title="Editar Lote '{{$lote->numero_lote}}'">
    <x-lote.forms :action="route('lote.update', ['lote' => $lote->id_lote])"
                      :ingressos="$ingressos"
                      :numeroLote="$lote->numero_lote"
                      :quantidadeLote="$lote->quantidade_lote"
                      :quantidadeLoteDisponivel="$lote->quantidade_lote_disponivel"
                      :adicionalLote="$lote->adicional_lote"
                      :ativo="$lote->quantidade_disponivel"
                      :valor="$lote->valor"
                      :ingresso="$lote->id_ingresso"
                      :ingressoAtual="$ingressoAtual"
                        >
    </x-lote.forms>
</x-layout>


<!--
"nome" => "teste"
"quantidade_inicial" => 10
"quantidade_atual" => 5
"valor_custo" => "10.00"
"valor_venda" => "15.00"
-->
