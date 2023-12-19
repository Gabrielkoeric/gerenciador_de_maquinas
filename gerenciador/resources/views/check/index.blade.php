<x-layout title="Ingresso">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <div class="container">
        <div class="row justify-content-center">
            @foreach ($sqls as $sql)
                <div class="col-md-6">
                    <h1 class="text-center">{{$sql->nome_do_ingresso}}</h1>
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('storage/' . $sql->qrcode) }}" alt="imagem" class="img-fluid">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layout>


