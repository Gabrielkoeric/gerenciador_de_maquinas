<x-layout title="Novo RDP">
    <x-deploy.rdp.forms :action="route('deploy.rdp')" 
        :clientes="$clientes"
        :vms="$vms"
        ></x-deploy.rdp.forms>
</x-layout>
