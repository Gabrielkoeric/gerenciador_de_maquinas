<x-layout title="Ingressos">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('ingressos.create')}}" class="btn btn-dark my-3">Adicionar Ingresso</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Descrição</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Quantidade Disponivel</th>
                <th scope="col">Valor</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($ingressos as $ingresso)
                <tr>
                    <td><a href="{{ route('ingressos.edit', $ingresso->id_ingressos) }}" class="text-decoration-none text-dark">{{ $ingresso->id_ingressos }}</a></td>
                    <td><a href="{{ route('ingressos.edit', $ingresso->id_ingressos) }}" class="text-decoration-none text-dark">{{ $ingresso->nome }}</a></td>
                    <td><a href="{{ route('ingressos.edit', $ingresso->id_ingressos) }}" class="text-decoration-none text-dark">{{$ingresso->descricao }}</a></td>
                    <td><a href="{{ route('ingressos.edit', $ingresso->id_ingressos) }}" class="text-decoration-none text-dark">{{ $ingresso->quantidade }}</a></td>
                    <td><a href="{{ route('ingressos.edit', $ingresso->id_ingressos) }}" class="text-decoration-none text-dark">{{ $ingresso->quantidade_disponivel }}</a></td>
                    <td><a href="{{ route('ingressos.edit', $ingresso->id_ingressos) }}" class="text-decoration-none text-dark">{{ $ingresso->valor }}</a></td>
                    <td>
                        <span class="d-flex">
                            <form action="{{route('ingressos.destroy', $ingresso->id_ingressos)}}" method="post" class="ms-2">
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
