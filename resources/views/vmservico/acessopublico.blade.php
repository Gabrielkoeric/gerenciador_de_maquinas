<x-layout title="Info Cliente">

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Cliente</th>
            <th>Licenciado Até</th>
            <th>Versão</th>
            <th>Data da Última Atualização</th>
            <th>VM</th>
            <th>IP</th>
            <th>Porta</th>
            <th>Nome do Sistema</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($servicos as $servico)
            <tr>
                <td onclick="copiarTexto('{{ $servico->nome_cliente }}', this)" class="cursor-copy">
                    {{ $servico->nome_cliente }}
                </td>
                <td onclick="copiarTexto('{{ $servico->licenciado_ate }}', this)" class="cursor-copy">
                    {{ $servico->licenciado_ate }}
                </td>
                <td onclick="copiarTexto('{{ $servico->versao }}', this)" class="cursor-copy">
                    {{ $servico->versao }}
                </td>
                <td onclick="copiarTexto('{{ $servico->data_atualizacao }}', this)" class="cursor-copy">
                    {{ $servico->data_atualizacao }}
                </td>
                <td onclick="copiarTexto('{{ $servico->nome_vm }}', this)" class="cursor-copy">
                    {{ $servico->nome_vm }}
                </td>
                <td onclick="copiarTexto('{{ $servico->ip }}', this)" class="cursor-copy">
                    {{ $servico->ip }}
                </td>
                <td onclick="copiarTexto('{{ $servico->porta }}', this)" class="cursor-copy">
                    {{ $servico->porta }}
                </td>
                <td onclick="copiarTexto('{{ $servico->apelido }}_escalasoft', this)" class="cursor-copy">
                    {{ $servico->apelido . '_escalasoft' }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <script>
        function copiarTexto(texto, elemento) {
            navigator.clipboard.writeText(texto).then(() => {
                const original = elemento.innerText;
                elemento.innerText = 'Copiado!';
                elemento.classList.add('text-success');

                setTimeout(() => {
                    elemento.innerText = original;
                    elemento.classList.remove('text-success');
                }, 1000);
            }).catch(err => {
                alert('Erro ao copiar: ' + err);
            });
        }
    </script>

    <style>
        .cursor-copy {
            cursor: pointer;
        }
        .cursor-copy:hover {
            background-color: #91d5f5;
        }
    </style>

</x-layout>
