<x-layout title="Editar Acesso '{{$dados->link}}'">
    <x-acessos.forms :action="route('acessos.update', $dados->id_acesso)"
                        :link="$dados->link"
                        :usuario="$dados->usuario"
                        :senha="$dados->senha"
                        :descricao="$dados->descricao"                        
    >
    </x-acessos.forms>
</x-layout>