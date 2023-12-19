<x-layout title="Editar Cor '{{$cor->cores_nome}}'">
    <x-formulario.forms :action="route('cor.update', $cor)" :nome="$cor->cores_nome"></x-formulario.forms>
</x-layout>

