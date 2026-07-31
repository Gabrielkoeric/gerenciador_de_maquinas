<x-layout title="Configurar Nova Replicação Para Rio Do Sul">
    <x-repositorioreplicacao.forms :action="route('repositorioreplicacao.store')"
        :clientes="$clientes"
        ></x-repositorioreplicacao.forms>
</x-layout>