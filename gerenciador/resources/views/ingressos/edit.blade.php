<x-layout title="Editar Ingresso '{{$ingresso->nome}}'">
    <x-ingresso.forms :action="route('ingressos.update', ['ingresso' => $ingresso->id_ingressos])"
                      :nome="$ingresso->nome"
                      :descricao="$ingresso->descricao"
                      :quantidade="$ingresso->quantidade"
                      :quantidadeDisponivel="$ingresso->quantidade_disponivel"
                      :valor="$ingresso->valor">
    </x-ingresso.forms>
</x-layout>


<!--
"nome" => "teste"
"quantidade_inicial" => 10
"quantidade_atual" => 5
"valor_custo" => "10.00"
"valor_venda" => "15.00"
-->
