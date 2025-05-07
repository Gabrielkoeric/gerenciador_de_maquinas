<x-layout title="Novo Escala Server">
    <x-deploy.server.forms :action="route('deploy.server')" 
        :clientes="$clientes"
        :vms="$vms"
        :ultimaPorta="$ultimaPorta" 
        
        ></x-deploy.server.forms>
</x-layout>
