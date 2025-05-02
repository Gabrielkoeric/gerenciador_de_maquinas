<x-layout title="Nova VM">
    <x-vm.forms :action="route('vm.store')" 
        :servidores="$servidores"
        :ipslan="$ipslan"
        ></x-vm.forms>
</x-layout>