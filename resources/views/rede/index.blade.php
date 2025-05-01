<x-layout title="Redes">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('redes.create')}}" class="btn btn-dark my-3">Adicionar</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">IP</th>
                    <th scope="col">Mascara</th>
                    <th scope="col">Descrição</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($redes as $rede)
                    <tr>
                        <td><a href="{{ route('redes.edit', $rede->id_rede ) }}" class="text-decoration-none text-dark">{{ $rede->ip }}</a></td>
                        <td><a href="{{ route('redes.edit', $rede->id_rede) }}" class="text-decoration-none text-dark">{{ $rede->mascara }}</a></td>
                        <td><a href="{{ route('redes.edit', $rede->id_rede) }}" class="text-decoration-none text-dark">{{ $rede->descricao }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </ul>
</x-layout>



