<x-layout title="Compra">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <form method="post" action="{{ route('compra.store') }}">
        @csrf
        <div class="d-flex" style="gap: 20px; flex-wrap: wrap">
            @foreach ($estoques as $estoque)
                <div
                    class="card d-flex flex-column justify-content-center align-items-center p-4"
                    style="width: 18rem; border-radius: 20px; transition: all .2s ease"
                    onMouseOver="this.style.borderColor='#6c757d'"
                    onMouseOut="this.style.borderColor='#0000001a'"
                >
                    <div style="display: flex; align-items: center; height: 160px">
                        <img
                            class="card-img-top"
                            src="{{ asset('storage/' . $estoque->imagemProduto) }}"
                            alt="Imagem de capa do card"
                            style="max-width: 160px; height: auto; max-height: 160px"
                        >
                    </div>

                    <div class="card-body">
                        <h5 href="{{ route('estoque.index') }}" class="card-title" style="color: #495057">
                            {{ $estoque->nome }}
                            <div style="font-size: 17px; margin-top: 6px">R$: {{ $estoque->valor_venda }}</div>
                            <div class="container mt-4">
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary decrease" data-estoque-id="{{ $estoque->id_produto_estoque }}" style="border-color: #e9ecef">-</button>
                                    <input type="text" class="form-control text-center number" name="quantidade[]" value="0" readonly style="background: #fff; border-color: #e9ecef">
                                    <button type="button" class="btn btn-outline-secondary increase" data-estoque-id="{{ $estoque->id_produto_estoque }}" style="border-color: #e9ecef">+</button>
                                </div>
                            </div>
                            <input type="hidden" name="estoque_id[]" value="{{ $estoque->id_produto_estoque }}">
                            <div class="valor-venda" data-valorvenda="{{ $estoque->valor_venda }}"></div>
                        </h5>
                    </div>
                </div>
            @endforeach
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 80px;">
            <footer>
                <div id="total" style="margin-top: 20px; font-size: 18px; font-weight: bold">Total: <span style="color: green">R$ 0.00</span></div>
            </footer>
            <button type="submit" class="btn btn-primary" style="margin-top: 20px">Finalizar Pedido</button>
        </div>
    </form>


    <script>
        window.compraStoreRoute = '{{ route("compra.store") }}';
        window.csrfToken = '{{ csrf_token('enikiVJ56pv58gMeH6KFAwuVHsAULgp1FVFLQrga') }}';
    </script>

    <script src="{{ asset('js/script.js') }}"></script>

</x-layout>
