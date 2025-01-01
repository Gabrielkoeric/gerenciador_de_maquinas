<x-layout title="Home">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-lg-3 mb-4">
                <a href="{{ route('usuario.index') }}" class="text-decoration-none">
                    <div class="card text-center">
                        <img src="{{ asset('storage/home/users.png') }}" class="card-img-top" alt="Imagem de capa do card">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #495057">Usuários</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-lg-3 mb-4">
                <a href="{{ route('estoque.index') }}" class="text-decoration-none">
                    <div class="card text-center">
                        <img src="{{ asset('storage/home/estoque.png') }}" class="card-img-top" alt="Imagem de capa do card">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #495057">Estoque</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-lg-3 mb-4">
                <a href="{{ route('compra.index') }}" class="text-decoration-none">
                    <div class="card text-center">
                        <img src="{{ asset('storage/home/fazerCompras.png') }}" class="card-img-top" alt="Imagem de capa do card">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #495057">Compras</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-lg-3 mb-4">
                <a href="{{ route('vendas.index') }}" class="text-decoration-none">
                    <div class="card text-center">
                        <img src="{{ asset('storage/home/minhasVendas.png') }}" class="card-img-top" alt="Imagem de capa do card">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #495057">Vendas (adm)</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-lg-3 mb-4">
                <a href="{{ route('pedidos.index') }}" class="text-decoration-none">
                    <div class="card text-center">
                        <img src="{{ asset('storage/home/minhasCompras.png') }}" class="card-img-top" alt="Imagem de capa do card">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #495057">Pedidos (usuario final)</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-lg-3 mb-4">
                <a href="{{ route('produtos.index') }}" class="text-decoration-none">
                    <div class="card text-center">
                        <img src="{{ asset('storage/home/produtos.png') }}" class="card-img-top" alt="Imagem de capa do card">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #495057">Produtos Retirada</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-lg-3 mb-4">
                <a href="{{ route('ingressos.index') }}" class="text-decoration-none">
                    <div class="card text-center">
                        <img src="{{ asset('storage/home/ingresso.png') }}" class="card-img-top" alt="Imagem de capa do card">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #495057">Ingressos</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-lg-3 mb-4">
                <a href="{{ route('lote.index') }}" class="text-decoration-none">
                    <div class="card text-center">
                        <img src="{{ asset('storage/home/lote.png') }}" class="card-img-top" alt="Imagem de capa do card">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #495057">Lote</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-layout>
