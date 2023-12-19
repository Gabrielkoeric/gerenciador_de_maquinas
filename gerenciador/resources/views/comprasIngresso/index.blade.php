<x-layout title="Compra Ingressos">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <form method="post" action="{{ route('compra_ingressos.store') }}">
        @csrf
        <div>
            @foreach ($resultados as $resultado)
                <div class="card d-flex flex-column justify-content-center align-items-center p-2" style="width: 18rem;">
                    <div class="card-body">
                        <h5 href="{{ route('compra_ingressos.index') }}" class="card-title" style="color: #495057">
                            <div>{{ $resultado->nome }}</div><br>
                            <div>{{$resultado -> descricao}}</div><br>
                            <div>Lote:{{$resultado->numero_lote}}</div><br>
                            <div>R$: {{ $resultado->adicional_lote + $resultado->valor }}</div>
                            <div class="container mt-5">
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary decrease" data-estoque-id="{{ $resultado->id_lote }}">-</button>
                                    <input type="text" class="form-control text-center number" name="quantidade[]" value="0" readonly>
                                    <button type="button" class="btn btn-outline-secondary increase" data-estoque-id="{{ $resultado->id_lote }}">+</button>
                                </div>
                            </div>
                            <input type="hidden" name="lote_id[]" value="{{ $resultado->id_lote }}">
                            <div class="valor-venda" data-valorvenda="{{ $resultado->adicional_lote + $resultado->valor }}"></div>
                        </h5>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Finalizar Pedido</button>
    </form>

    <footer>
        <div id="total">Total: R$ 0.00</div>
    </footer>

    <script src="{{ asset('js/ingressos.js') }}"></script>

</x-layout>
