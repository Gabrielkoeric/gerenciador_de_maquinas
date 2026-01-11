<x-layout title="Editar Cliente '{{$dados->nome}}'">
    <x-cliente.forms :action="route('cliente.update', $dados->id_cliente_escala)"
                        :nome="$dados->nome"
                        :apelido="$dados->apelido"
                        :porta="$dados->porta_rdp"
                        :licenca="$dados->licenca"
                        :coletor="$dados->coletor"
                        :desktop="$dados->desktop"
                        :ativo="$dados->ativo"
    >
    </x-cliente.forms>
</x-layout>