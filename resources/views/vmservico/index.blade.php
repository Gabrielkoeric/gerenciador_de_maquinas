<x-layout title="Serviço da VM">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <form id="servicoForm" action="{{ route('vmservico.executarComando') }}" method="POST">
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
                <th scope="col">Tipo</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($servicos as $servico)
                <tr>
                    <td>
                        <input type="checkbox" name="servicos[]" class="selectItem" value="{{ $servico->id_servico_vm }}">
                    </td>
                    <td>{{ $servico->cliente_nome }}</td>
                    <td>{{ $servico->servico_nome }}</td>
                    <td>{{ $servico->vm_nome }}</td>
                    <td>{{ $servico->porta }}</td>
                    <td>{{ $servico->tipo }}</td>
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
