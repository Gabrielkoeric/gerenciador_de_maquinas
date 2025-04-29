<x-layout title="Editar Cliente '{{$dados->nome}}'">
    <x-cliente.forms :action="route('cliente_escala.update', $dados->id_cliente_escala)"
                        :nome="$dados->nome"
                        :sigla="$dados->sigla"
                        :licenca="$dados->licenca"
                        :coletor="$dados->coletor"
                        :desktop="$dados->desktop"
                        :ativo="$dados->ativo"
    >
    </x-cliente.forms>
</x-layout>