<x-layout title="Novo Escala Swarm">
    <x-deploy.swarm.forms :action="route('deploy.swarm')" 
        :clientes="$clientes"
        :vms="$vms"
        
        ></x-deploy.swarm.forms>
</x-layout>
