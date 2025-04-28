<x-layout title="Config Geral">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('config_geral.create')}}" class="btn btn-dark my-3">Adicionar</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome Config</th>
                    <th scope="col">Valor Config</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($configGeral as $config)
                    <tr>
                        <td><a href="{{ route('config_geral.edit', $config->id_config_geral ) }}" class="text-decoration-none text-dark">{{ $config->id_config_geral }}</a></td>
                        <td><a href="{{ route('config_geral.edit', $config->id_config_geral) }}" class="text-decoration-none text-dark">{{ $config->nomeConfig }}</a></td>
                        <td><a href="{{ route('config_geral.edit', $config->id_config_geral) }}" class="text-decoration-none text-dark">{{ $config->valorConfig }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </ul>
</x-layout>



