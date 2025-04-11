<x-layout title="Serviço da VM">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('vm_servico.create')}}" class="btn btn-dark my-3">Adicionar</a>
    <a href="{{route('logs_execucoes.index')}}" class="btn btn-dark my-3">Logs Execuçoes</a>

    <form id="servicoForm" action="{{ route('vm_servico.executar') }}" method="POST">
        @csrf
        <input type="hidden" name="acao" id="acaoInput">
        
        <button type="button" class="btn btn-dark my-3" onclick="submeterFormulario('status')">Status</button>
        <button type="button" class="btn btn-dark my-3" onclick="submeterFormulario('stop')">Parar</button>
        <button type="button" class="btn btn-dark my-3" onclick="submeterFormulario('start')">Iniciar</button>
        <button type="button" class="btn btn-dark my-3" onclick="submeterFormulario('restart')">Restart</button>

        <table class="table table-striped">
            <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th scope="col">Cliente</th>
                <th scope="col">Serviço</th>
                <th scope="col">VM</th>
                <th scope="col">Porta</th>
                <th scope="col">Status</th>
                <th scope="col">Data</th>
                <th scope="col">PID</th>
                <th scope="col">Execução</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($servicos as $servico)
                <tr>
                    <td>
                        <input type="checkbox" name="servicos[]" class="selectItem" value="{{ $servico->id_servico_vm }}">
                    </td>
                    <td><a href="{{ route('vm_servico.edit', $servico->id_servico_vm ) }}" class="text-decoration-none text-dark">{{ $servico->nome_cliente }}</a></td>
                    <td><a href="{{ route('vm_servico.edit', $servico->id_servico_vm ) }}" class="text-decoration-none text-dark">{{ $servico->nome }}</a></td>
                    <td><a href="{{ route('vm_servico.edit', $servico->id_servico_vm ) }}" class="text-decoration-none text-dark">{{ $servico->nome_vm }}</a></td>
                    <td><a href="{{ route('vm_servico.edit', $servico->id_servico_vm ) }}" class="text-decoration-none text-dark">{{ $servico->porta }}</a></td>
                    <td>{{ $servico->status }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </form>

    <script>
        document.getElementById('selectAll').addEventListener('change', function () {
            let checkboxes = document.querySelectorAll('.selectItem');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        function submeterFormulario(acao) {
            let form = document.getElementById('servicoForm');
            document.getElementById('acaoInput').value = acao;
            form.submit();
        }
    </script>
</x-layout>
