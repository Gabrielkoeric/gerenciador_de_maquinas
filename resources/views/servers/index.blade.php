<x-layout title="Servers Fisicos">
<div class="d-flex align-items-start my-3">
    <a href="{{ route('home.index') }}" class="btn btn-dark me-1">Home</a>
    <a href="{{ route('server.create') }}" class="btn btn-dark me-1">Adicionar</a>
    <form action="{{ route('server.executarComando') }}" method="POST" class="mb-0 me-1">
        @csrf
        <button type="submit" class="btn btn-dark">Buscar VM</button>
    </form>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
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
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico ) }}" class="text-decoration-none text-dark">{{ $server->nome }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->usuario }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->senha }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->dominio }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->iplan }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->ipwan }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_servidor_fisico) }}" class="text-decoration-none text-dark">{{ $server->porta }}</a></td>
                        <td>
                            @if ($server->tipo === 'ssh')
                        <a href="{{ route('server.ssh', $server->id_servidor_fisico) }}" class="btn btn-primary btn-sm">SSH</a>
                        @elseif ($server->tipo === 'rdp')
                        <button class="btn btn-success btn-sm" onclick="copyRDPCommand('{{ $server->ipwan }}', '{{ $server->porta }}', '{{ $server->usuario }}', '{{ $server->senha }}')">RDP</button>
                        @endif
                        </td>

                        <td>
                        <span class="d-flex">
                            <!--<form action="{{route('usuario.destroy', $server->id_servidor_fisico)}}" method="post" class="ms-2">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Excluir</button>
                            </form>-->
                        </span>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>

    </ul>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var clipboard = new ClipboardJS('.btn-outline-secondary');

            clipboard.on('success', function (e) {
                alert('Copiado para a área de transferência!');
                e.clearSelection();
            });

            clipboard.on('error', function (e) {
                alert('Erro ao copiar para a área de transferência. Tente manualmente.');
            });
        });
    </script>

    <script>
        function copyToClipboard(value) {
            var tempInput = document.createElement('input');
            tempInput.value = value;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
        }
    </script>

<script>
    function copyRDPCommand(ip, port, user, password) {
        var command = `cmdkey /generic:TERMSRV/${ip} /user:${user} /pass:${password}; Start-Process mstsc -ArgumentList "/v:${ip}:${port}"`;
        var tempInput = document.createElement('textarea');
        tempInput.value = command;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        alert("Comando copiado! Abra o PowerShell (Win + R, digite 'powershell') e cole o comando para conectar.");
    }
</script>

</x-layout>