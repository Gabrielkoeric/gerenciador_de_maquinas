<x-layout title="Editar Servico '{{$servico->nome}}'">
    <x-servico.forms :action="route('servico.update', $servico->id_servico)"
                        :nome="$servico->nome"
    >
    </x-servico.forms>
</x-layout>