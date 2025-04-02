<x-layout title="Editar VM '{{ $dados->nome }}'"> 

    <x-vm.forms :action="route('vm.update', $dados->id_servidor_fisico)"
                        :nome="$dados->nome"
                        :dns="$dados->dns"
                        :ipwan="$dados->ipwan"
                        :iplan="$dados->iplan"
                        :porta="$dados->porta"
                        :dominio="$dados->dominio"
                        :tipo="$dados->tipo"
                        :usuario="$dados->usuario"
                        :senha="$dados->senha"
                        :servidores="$servidores"
                        :servidorAtual="$servidorAtual"
    >
    </x-vm.forms>
</x-layout>