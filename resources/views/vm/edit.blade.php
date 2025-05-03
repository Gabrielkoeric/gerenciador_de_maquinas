<x-layout title="Editar VM '{{ $dados->nome }}'"> 

    <x-vm.forms :action="route('vm.update', $dados->id_vm)"
                        :nome="$dados->nome"
                        :ipslan="$ipslan"
                        :iplanAtual="$iplanAtual"
                        :porta="$dados->porta"
                        :dominio="$dados->id_dominio"
                        :tipo="$dados->tipo"
                        :so="$dados->so"
                        :autostart="$dados->autostart"
                        :usuario="$dados->usuario"
                        :senha="$dados->senha"
                        :servidores="$servidores"
                        :servidorAtual="$servidorAtual"
    >
    </x-vm.forms>
</x-layout>