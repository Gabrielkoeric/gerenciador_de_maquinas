<x-layout title="Editar Servidor Físico '{{ $dados->nome }}'"> 

    <x-servers.forms :action="route('server.update', $dados->id_servidor_fisico)"
                        :nome="$dados->nome"
                        :dns="$dados->dns"
                        :ipwan="$dados->ipwan"
                        :iplan="$dados->iplan"
                        :porta="$dados->porta"
                        :dominio="$dados->dominio"
                        :tipo="$dados->tipo"
                        :usuario="$dados->usuario"
                        :senha="$dados->senha"
                    

    >
    </x-servers.forms>
</x-layout>