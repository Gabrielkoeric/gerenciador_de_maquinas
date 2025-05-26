<x-layout title="Editar Servidor Físico '{{ $dados->nome }}'"> 
    <x-servers.forms 
        :action="route('server.update', $dados->id_servidor_fisico)"
        :nome="$dados->nome"
        :ipswan="$ipswan"
        :ipslan="$ipslan"
        :iplanAtual="$iplanAtual"
        :ipwanAtual="$ipwanAtual"
        :porta="$dados->porta"
        :dominio="$dados->id_dominio"
        :tipo="$dados->tipo"
        :mac="$dados->mac"
        :serial="$dados->serial"
        :usuario="$dados->usuario"
        :senha="$dados->senha"
    />
</x-layout>
