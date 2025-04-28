<x-layout title="VM">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('vm.create')}}" class="btn btn-dark my-3">Adicionar</a>
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
                    <th scope="col">Porta</th>
                    <th scope="col">Srv Fisico</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">AutoStart</th>
                    <th scope="col">Acesso</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($vms as $vm)
                    <tr>
                        <td><a href="{{ route('vm.edit', $vm->id_vm ) }}" class="text-decoration-none text-dark">{{ $vm->nome }}</a></td>
                        <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->usuario }}</a></td>
                        <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->senha }}</a></td>
                        <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->dominio }}</a></td>
                        <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->iplan }}</a></td>
                        <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->porta }}</a></td>
                        <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->servidor_nome }}</a></td>
                        <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->tipo }}</a></td>
                        <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->autostart }}</a></td>
                        <td>
                            @if ($vm->so === 'ssh')
                        <a href="{{ route('conecta.ssh', $vm->id_vm) }}" class="btn btn-primary btn-sm">SSH</a>
                        @elseif ($vm->so === 'rdp')
                        <button class="btn btn-success btn-sm" onclick="copyRDPCommand('{{ $vm->iplan }}', '{{ $vm->porta }}', '{{ $vm->usuario }}', '{{ $vm->senha }}')">RDP</button>
                        @endif
                        </td>

                        <td>
                        <span class="d-flex">
                            <!--<form action="{{route('usuario.destroy', $vm->id_vm)}}" method="post" class="ms-2">
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