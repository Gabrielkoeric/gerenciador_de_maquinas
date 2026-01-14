<x-layout title="Gera RDP">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('cliente.index')}}" class="btn btn-dark my-3 pr">Cliente</a>
    <div class="container">
    

    <form method="POST" action="{{ route('cliente.gerardp.post') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Domínio</label>
            <input type="text"
                   name="dominio"
                   class="form-control"
                   placeholder="ex: .cloud.escalasoft.com.br"
                   required>
        </div>

        <button class="btn btn-success">
            Gerar RDP
        </button>
    </form>
</div>
</x-layout>