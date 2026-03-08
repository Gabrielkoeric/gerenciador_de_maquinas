<x-layout title="Usuarios Logados">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Cliente</th>
                    <th scope="col">Data</th>
                    <th scope="col">Quantidade</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($usuariosLogados as $usuarioLogado)
                    <tr>
                        <td>{{ $usuarioLogado->cliente_nome }}</td>
                        <td>{{ $usuarioLogado->data }}</td>
                        <td>{{ $usuarioLogado->quantidade }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </ul>
</x-layout>



