<x-layout title="Confirmar Entrega">
    <a href="{{ route('home.index') }}" class="btn btn-dark my-3 pr">Home</a>

    <form method="post" action="{{ route('produtos.concluido', ['hash' => $hash]) }}">
        @csrf
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Produto</th>
                <th scope="col">Quantidade</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($dados as $dado)
                <tr>
                    <td>{{ $dado->nome_do_estoque }}</td>
                    <td>{{ $dado->quantidade_retirada }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Entregue</button>
    </form>
</x-layout>
