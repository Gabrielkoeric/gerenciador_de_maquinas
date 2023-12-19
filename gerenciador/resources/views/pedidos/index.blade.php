<x-layout title="Pedidos">
    <a href="{{ route('home.index') }}" class="btn btn-dark my-3">Home</a>

    @foreach ($compras->unique('id_compra') as $compra)
        <div class="card mb-4">
            <div class="card-header">
                Compra número: {{ $compra->id_compra }}
            </div>
            <div class="card-body">
                <h5 class="card-title">Status de Pagamento: {{ $compra->status }}</h5>
                <h6 class="card-title">Valor: R$ {{ number_format($compra->valor, 2, ',', '.') }}</h6>
                @if ($compra->status !== 'approved')
                    <a href="{{$compra->link_pagamento}}" class="btn btn-primary">Realizar Pagamento</a>
                @endif

                <ul class="list-group my-4">
                    @foreach ($compras->where('id_compra', $compra->id_compra) as $produto)
                        <li class="list-group-item">{{ $produto->nome_produto }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
</x-layout>

