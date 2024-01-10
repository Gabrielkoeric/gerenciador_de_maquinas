<x-layout title="Servers Fisicos">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('server.create')}}" class="btn btn-dark my-3">Adicionar</a>
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
                    <th scope="col">IP Lan</th>
                    <th scope="col">IP Publico</th>
                    <th scope="col">Porta</th>
                    <th scope="col">Processador</th>
                    <th scope="col">Memória</th>
                    <th scope="col">Acesso SSH</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($servers as $server)
                    <tr>
                        <td><a href="{{ route('server.edit', $server->id_server ) }}" class="text-decoration-none text-dark">{{ $server->nome_server }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_server) }}" class="text-decoration-none text-dark">{{ $server->usuario }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_server) }}" class="text-decoration-none text-dark">{{ $server->senha }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_server) }}" class="text-decoration-none text-dark">{{ $server->ip_lan }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_server) }}" class="text-decoration-none text-dark">{{ $server->ip_publico }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_server) }}" class="text-decoration-none text-dark">{{ $server->porta }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_server) }}" class="text-decoration-none text-dark">{{ $server->processador }}</a></td>
                        <td><a href="{{ route('server.edit', $server->id_server) }}" class="text-decoration-none text-dark">{{ $server->memoria }}</a></td>
                        <td><a href="{{ route('server.ssh', $server->id_server) }}" class="btn btn-primary btn-sm">SSH</a></td>
                        <td>
                        <span class="d-flex">
                            <!--<form action="{{route('usuario.destroy', $server->id_server)}}" method="post" class="ms-2">
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

</x-layout>



