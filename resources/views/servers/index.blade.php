<x-layout title="Servers Fisicos">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('server.create')}}" class="btn btn-dark my-3">Adicionar</a>
    <!--<a href="{{route('logs_execucoes.index')}}" class="btn btn-dark my-3">Logs Execuçoes</a>-->

    <form id="serverForm" action="{{ route('server.executar') }}" method="POST">
        @csrf
        <input type="hidden" name="acao" id="acaoInput">
        
        <button type="button" class="btn btn-dark my-3" data-action onclick="confirmAction('status')">Status</button>
        <button type="button" class="btn btn-dark my-3" data-action onclick="confirmAction('stop')">Parar</button>
        <!--<button type="button" class="btn btn-dark my-3" data-action onclick="confirmAction('start')">Iniciar</button>-->
        <button type="button" class="btn btn-dark my-3" data-action onclick="confirmAction('restart')">Restart</button>
        <button type="button" class="btn btn-dark my-3" data-action onclick="confirmAction('listaVm')">Lista VM</button>
        <button type="button" class="btn btn-dark my-3" data-action onclick="confirmAction('realocaVm')">Realoca VM</button>

        <table class="table table-striped">
            <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th scope="col">Nome</th>
                <th scope="col">Usuario Local</th>
                <th scope="col">Senha Local</th>
                <th scope="col">Dominio</th>
                <th scope="col">Usuario Dominio</th>
                <th scope="col">Senha Dominio</th>
                <th scope="col">IP Lan</th>
                <th scope="col">IP Wan</th>
                <th scope="col">Porta</th>
                <th scope="col">MAC</th>
                <th scope="col">Serial</th>
                <th scope="col">Autostart</th>
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
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->usuario_servidor }}</a></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" onclick="copyToClipboard(this)" data-senha="{{ $server->senha_servidor }}">Senha</button>
                        </td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->dominio_nome }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->dominio_usuario }}</a></td>
                        <td>
                            @if (!empty($server->dominio_senha))
                                <button type="button" class="btn btn-info btn-sm" onclick="copyToClipboard(this)" data-senha="{{ $server->dominio_senha }}">Senha Domínio</button>
                            @endif
                        </td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->ip_lan }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->ip_wan }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->porta }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->mac }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->serial }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->autostart }}</a></td>
                        <td>
                            @if ($server->tipo === 'ssh')
                        <a href="{{ route('conecta.ssh', $server->id_servidor_fisico) }}" class="btn btn-primary btn-sm">SSH</a>
                        @elseif ($server->tipo === 'rdp')
                            <button type="button" class="btn btn-success btn-sm" onclick="copyRDPCommand('{{ $server->ip_wan }}', '{{ $server->porta }}', '{{ $server->dominio_usuario }}', '{{ $server->dominio_senha }}')">RDP</button>
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

        // Função de confirmação
        function confirmAction(acao) {
            const confirmacao = confirm(`Você está prestes a executar um ${acao} em um host físico. Deseja continuar?`);
            if (confirmacao) {
                submeterFormulario(acao);
            }
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

    <script>
        function copyToClipboard(element) {
            const value = element.getAttribute('data-senha');

            if (!value) {
                alert('Senha não disponível.');
                return;
            }

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(value)
                    .then(() => {
                        alert('Senha copiada para a área de transferência!');
                    })
                    .catch(() => fallbackCopy(value));
            } else {
                fallbackCopy(value);
            }
        }

        function fallbackCopy(value) {
            const tempInput = document.createElement('textarea');
            tempInput.value = value;
            document.body.appendChild(tempInput);
            tempInput.focus();
            tempInput.select();

            try {
                document.execCommand('copy');
                alert('Senha copiada para a área de transferência!');
            } catch (err) {
                alert('Erro ao copiar a senha.');
            }

            document.body.removeChild(tempInput);
        }
    </script>
</x-layout>
