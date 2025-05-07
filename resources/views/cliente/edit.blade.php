<x-layout title="Editar Cliente '{{$dados->nome}}'">
    <x-cliente.forms :action="route('cliente_escala.update', $dados->id_cliente_escala)"
                        :nome="$dados->nome"
                        :apelido="$dados->apelido"
                        :licenca="$dados->licenca"
                        :coletor="$dados->coletor"
                        :desktop="$dados->desktop"
                        :ativo="$dados->ativo"
    >
    </x-cliente.forms>
</x-layout>