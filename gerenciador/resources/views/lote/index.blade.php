<x-layout title="Lote">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('lote.create')}}" class="btn btn-dark my-3">Adicionar Lote</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Numero Lote</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Quantidade Disponivel</th>
                <th scope="col">Ativo</th>
                <th scope="col">Adicional Lote</th>
                <th scope="col">Ingresso</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($lotes as $lote)
                <tr>
                    <td><a href="{{ route('lote.edit', $lote->id_lote) }}" class="text-decoration-none text-dark">{{ $lote->id_lote }}</a></td>
                    <td><a href="{{ route('lote.edit', $lote->id_lote) }}" class="text-decoration-none text-dark">{{ $lote->numero_lote }}</a></td>
                    <td><a href="{{ route('lote.edit', $lote->id_lote) }}" class="text-decoration-none text-dark">{{$lote->quantidade_lote }}</a></td>
                    <td><a href="{{ route('lote.edit', $lote->id_lote) }}" class="text-decoration-none text-dark">{{ $lote->quantidade_lote_disponivel }}</a></td>
                    <td><a href="{{ route('lote.edit', $lote->id_lote) }}" class="text-decoration-none text-dark">{{ $lote->ativo }}</a></td>
                    <td><a href="{{ route('lote.edit', $lote->id_lote) }}" class="text-decoration-none text-dark">{{ $lote->adicional_lote }}</a></td>
                    <td><a href="{{ route('lote.edit', $lote->id_lote) }}" class="text-decoration-none text-dark">{{ $lote->nome_ingresso }}</a></td>
                    <td>
                        <span class="d-flex">
                            <form action="{{route('lote.destroy', $lote->id_lote)}}" method="post" class="ms-2">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </span>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

    </ul>
</x-layout>
