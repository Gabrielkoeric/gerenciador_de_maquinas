<x-layout title="Servers Fisicos">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('server.create')}}" class="btn btn-dark my-3">Adicionar</a>
    <a href="{{route('logs_execucoes.index')}}" class="btn btn-dark my-3">Logs Execuçoes</a>

    <form id="serverForm" action="{{ route('server.executar') }}" method="POST">
        @csrf
        <input type="hidden" name="acao" id="acaoInput">
        
        <button type="button" class="btn btn-dark my-3" data-action onclick="submeterFormulario('status')">Status</button>
        <button type="button" class="btn btn-dark my-3" data-action onclick="submeterFormulario('stop')">Parar</button>
        <button type="button" class="btn btn-dark my-3" data-action onclick="submeterFormulario('start')">Iniciar</button>
        <button type="button" class="btn btn-dark my-3" data-action onclick="submeterFormulario('restart')">Restart</button>

        <table class="table table-striped">
            <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th scope="col">Nome</th>
                <th scope="col">Usuario</th>
                <th scope="col">Senha</th>
                <th scope="col">Dominio</th>
                <th scope="col">IP Lan</th>
                <th scope="col">IP Wan</th>
                <th scope="col">Porta</th>
                <th scope="col">Acesso</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($servers as $server)
                <tr>
                    <td>
                        <input type="checkbox" name="server[]" class="selectItem" value="{{ $server->id_servidor_fisico }}">
                    </td>
                    <td><a href="{{ route('server.edit', $server->id_servidor_fisico ) }}" class="text-decoration-none text-dark">{{ $server->nome }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->usuario }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->senha }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->id_dominio }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->ip_lan }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->ip_wan }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->porta }}</a></td>
                        <td>
                            @if ($server->tipo === 'ssh')
                        <a href="{{ route('conecta.ssh', $server->id_servidor_fisico) }}" class="btn btn-primary btn-sm">SSH</a>
                        @elseif ($server->tipo === 'rdp')
                            <button type="button" class="btn btn-success btn-sm" onclick="copyRDPCommand('{{ $server->ip_wan }}', '{{ $server->porta }}', '{{ $server->usuario }}', '{{ $server->senha }}')">RDP</button>
                        @endif
                        </td>
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
            let form = document.getElementById('serverForm');
            document.getElementById('acaoInput').value = acao;
            form.submit();
        }
    </script>

<script>
    const checkboxes = document.querySelectorAll('.selectItem');
    const actionButtons = document.querySelectorAll('button[data-action]');

    function toggleButtons() {
        const isAnyChecked = [...checkboxes].some(cb => cb.checked);
        actionButtons.forEach(btn => btn.disabled = !isAnyChecked);
    }

    // Escuta alteração em cada checkbox
    checkboxes.forEach(cb => cb.addEventListener('change', toggleButtons));
    // Escuta também o selectAll
    document.getElementById('selectAll').addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        toggleButtons();
    });

    // Desabilita ao carregar
    window.onload = toggleButtons;
</script>

</x-layout>
