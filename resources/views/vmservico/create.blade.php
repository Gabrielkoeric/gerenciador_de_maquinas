<x-layout title="Novo Servico">
    <x-vmservico.forms :action="route('vm_servico.store')" :vms="$vms"
                                                          :servicos="$servicos"
                                                          :clientes="$clientes"
    
    ></x-vmservico.forms>
</x-layout>
