<x-layout title="Serviços">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('servico.create')}}" class="btn btn-dark my-3">Adicionar</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($servicos as $servico)
                    <tr>
                        <td><a href="{{ route('servico.edit', $servico->id_servico ) }}" class="text-decoration-none text-dark">{{ $servico->id_servico }}</a></td>
                        <td><a href="{{ route('servico.edit', $servico->id_servico) }}" class="text-decoration-none text-dark">{{ $servico->nome }}</a></td>
                    </tr>
                @endforeach

                </tbody>
            </table>
    </ul>
</x-layout>



