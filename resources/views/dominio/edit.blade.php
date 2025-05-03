<x-layout title="Editar Dominio '{{$dominio->nome}}'">
    <x-dominio.forms :action="route('dominios.update', $dominio->id_dominio)"
                        :nome="$dominio->nome"
                        :usuario="$dominio->usuario"
                        :senha="$dominio->senha"
    >
    </x-dominio.forms>
</x-layout>