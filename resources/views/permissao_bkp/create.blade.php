<x-layout title="Nova Liberação de Bkp">
    <x-permissao_bkp.forms :action="route('permissao_bkp.store')"
        :clientes="$clientes"
        :usuarios="$usuarios"
    ></x-permissao_bkp.forms>
</x-layout>