<x-layout title="Seções Cloud">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('secao_cloud.create')}}" class="btn btn-dark my-3">Adicionar</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Usuario</th>
                    <th scope="col">Senha</th>
                    <th scope="col">Cliente</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($dados as $dado)
                    <tr>
                        <td><a href="{{ route('secao_cloud.edit', $dado->id_secao_cloud ) }}" class="text-decoration-none text-dark">{{ $dado->usuario }}</a></td>
                        <td><a href="{{ route('secao_cloud.edit', $dado->id_secao_cloud) }}" class="text-decoration-none text-dark">{{ $dado->senha }}</a></td>
                        <td><a href="{{ route('secao_cloud.edit', $dado->id_secao_cloud) }}" class="text-decoration-none text-dark">{{ $dado->nome_cliente }}</a></td>
                        <td>
            <form action="{{ route('secao_cloud.destroy', $dado->id_secao_cloud) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
            </form>
        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
    </ul>
</x-layout>



