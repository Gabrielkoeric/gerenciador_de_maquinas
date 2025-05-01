<x-layout title="Editar Servidor Físico '{{ $dados->nome }}'"> 
    <x-servers.forms 
        :action="route('server.update', $dados->id_servidor_fisico)"
        :nome="$dados->nome"
        :ipswan="$ipswan"
        :ipslan="$ipslan"
        :iplanAtual="$iplanAtual"
        :ipwanAtual="$ipwanAtual"
        :porta="$dados->porta"
        :dominio="$dados->dominio"
        :tipo="$dados->tipo"
        :usuario="$dados->usuario"
        :senha="$dados->senha"
    />
</x-layout>
