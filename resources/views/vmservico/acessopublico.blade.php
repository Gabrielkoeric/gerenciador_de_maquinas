<x-layout title="Info Cliente">

        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Cliente</th>
                <th scope="col">Versão</th>
                <th scope="col">VM</th>
                <th scope="col">IP</th>
                <th scope="col">Porta</th>
                <th scope="col">Nome do Sistema</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($servicos as $servico)
                <tr>
                    <td><a class="text-decoration-none text-dark">{{ $servico->nome_cliente }}</a></td>
                    <td><a class="text-decoration-none text-dark">{{ $servico->versao }}</a></td>
                    <td><a class="text-decoration-none text-dark">{{ $servico->nome_vm }}</a></td>
                    <td><a class="text-decoration-none text-dark">{{ $servico->ip }}</a></td>
                    <td><a class="text-decoration-none text-dark">{{ $servico->porta }}</a></td>
                    <td><a class="text-decoration-none text-dark">{{ $servico->apelido . '_escalasoft'}}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
</x-layout>