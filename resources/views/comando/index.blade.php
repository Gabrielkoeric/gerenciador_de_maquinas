<x-layout title="Comando">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('comando.create')}}" class="btn btn-dark my-3">Adicionar</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Ação</th>
                    <th scope="col">Comando</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($comandos as $comando)
                    <tr>
                        <td><a href="{{ route('comando.edit', $comando->id_comando_execucao_remota ) }}" class="text-decoration-none text-dark">{{ $comando->id_comando_execucao_remota }}</a></td>
                        <td><a href="{{ route('comando.edit', $comando->id_comando_execucao_remota) }}" class="text-decoration-none text-dark">{{ $comando->tipo }}</a></td>
                        <td><a href="{{ route('comando.edit', $comando->id_comando_execucao_remota ) }}" class="text-decoration-none text-dark">{{ $comando->acao }}</a></td>
                        <td><a href="{{ route('comando.edit', $comando->id_comando_execucao_remota) }}" class="text-decoration-none text-dark">{{ $comando->comando}}</a></td>
                    </tr>
                @endforeach

                </tbody>
            </table>
    </ul>
</x-layout>



