<x-layout title="Serviço da VM">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('server.create')}}" class="btn btn-dark my-3">Adicionar</a>
    <a href="{{route('server.create')}}" class="btn btn-dark my-3">Status</a>
    <a href="{{route('server.create')}}" class="btn btn-dark my-3">Parar</a>
    <a href="{{route('server.create')}}" class="btn btn-dark my-3">Iniciar</a>
    <a href="{{route('server.create')}}" class="btn btn-dark my-3">Restart</a>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Serviço</th>
                    <th scope="col">VM</th>
                    <th scope="col">Porta</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($servicos as $servico)
                    <tr>
                        <td><input type="checkbox" class="selectItem" value="{{ $servico->id_servico_vm }}"></td>
                        <td><a href="{{ route('server.edit', $servico->id_servico_vm ) }}" class="text-decoration-none text-dark">{{ $servico->cliente_nome }}</a></td>
                        <td><a href="{{ route('server.edit', $servico->id_servico_vm) }}" class="text-decoration-none text-dark">{{ $servico->servico_nome }}</a></td>
                        <td><a href="{{ route('server.edit', $servico->id_servico_vm) }}" class="text-decoration-none text-dark">{{ $servico->vm_nome }}</a></td>
                        <td><a href="{{ route('server.edit', $servico->id_servico_vm) }}" class="text-decoration-none text-dark">{{ $servico->porta }}</a></td>
                        <td><a href="{{ route('server.edit', $servico->id_servico_vm) }}" class="text-decoration-none text-dark">{{ $servico->tipo }}</a></td>
                        <td>
                        <span class="d-flex">
                            <!--<form action="{{route('usuario.destroy', $servico->id_servico_vm)}}" method="post" class="ms-2">
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
        document.getElementById('selectAll').addEventListener('change', function () {
            let checkboxes = document.querySelectorAll('.selectItem');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>

</x-layout>