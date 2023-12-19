<x-layout title="Gera Pedido">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{ route('gera.create') }}" class="btn btn-dark my-3">Nova Compra</a>

    <table class="table">
        <thead>
        <tr>
            <th>ID Compra</th>
            <th>Valor</th>
            <th>Status</th>
            <th>Link de Pagamento</th>
        </tr>
        </thead>
        <tbody>
        @foreach($compras as $compra)
            <tr>
                <td>{{ $compra->id_compra }}</td>
                <td>{{ $compra->valor }}</td>
                <td>{{ $compra->status }}</td>
                <td>
                    <a href="{{ $compra->link_pagamento }}" class="btn btn-primary" target="_blank">
                        Pagar
                    </a>
                </td>
                <td>
                    @if ($compra->entregue == 0)
                        <form action="{{ route('gera.update', ['gera' => $compra->id_compra]) }}" method="post">
                            @csrf
                            @method('put')
                            <button type="submit" class="btn btn-success">Entregue</button>
                        </form>
                    @else
                        Entregue
                    @endif
                </td>
            </tr>
        @endforeach


        </tbody>
    </table>
</x-layout>

