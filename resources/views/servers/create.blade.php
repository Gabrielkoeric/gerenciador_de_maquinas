<x-layout title="Novo Servidor Fisico">
    <x-servers.forms :action="route('server.store')"
        :ipswan="$ipswan"
        :ipslan="$ipslan"
    ></x-servers.forms>
</x-layout>