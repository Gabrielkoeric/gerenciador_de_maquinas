<x-layout title="Dominios">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('dominios.create')}}" class="btn btn-dark my-3">Adicionar</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Nome Dominio</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Senha</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($dominios as $dominio)
                    <tr>
                        <td><a href="{{ route('dominios.edit', $dominio->id_dominio ) }}" class="text-decoration-none text-dark">{{ $dominio->nome }}</a></td>
                        <td><a href="{{ route('dominios.edit', $dominio->id_dominio) }}" class="text-decoration-none text-dark">{{ $dominio->usuario }}</a></td>
                        <td><a href="{{ route('dominios.edit', $dominio->id_dominio) }}" class="text-decoration-none text-dark">{{ $dominio->senha }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </ul>
</x-layout>



