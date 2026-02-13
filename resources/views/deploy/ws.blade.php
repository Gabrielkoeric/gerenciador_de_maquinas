<x-layout title="Novo Escala Web Service">
    <x-deploy.ws.forms :action="route('deploy.ws')" 
        :clientes="$clientes"
        :vms="$vms"
        :ultimaPorta="$ultimaPorta" 
        
        ></x-deploy.ws.forms>
</x-layout>
