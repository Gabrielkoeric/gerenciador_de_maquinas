<x-layout title="Editar Seção '{{$clienteAtual->usuario}}'">
    <x-secao.forms :action="route('secao_cloud.update', $clienteAtual->id_secao_cloud)"
                        :usuario="$clienteAtual->usuario"
                        :senha="$clienteAtual->senha"
                        :cliente="$clienteAtual->id_cliente_escala"
                        :clientes="$clientes"
                        :clienteAtual="$clienteAtual"
                        :coletor="$clienteAtual->coletor"
    >
    </x-secao.forms>
</x-layout>

