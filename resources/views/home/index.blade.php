<x-layout title="Home">
    <div class="container" style="margin-top: 40px">
        <div class="row">
            @foreach ($sqls as $sql)
                <div class="col-md-4 col-lg-3" style="margin-bottom: 20px;">
                    <a href="{{ route($sql->nome_tela . '.index') }}" class="text-decoration-none">
                        <div
                            class="flex card text-center"
                            style="justify-content: center;align-items: center; padding-top: 20px; border-radius: 12px; transition: all .2s ease"
                            onMouseOver="this.style.background='#f1f1f1'"
                            onMouseOut="this.style.background='#FFF'"
                        >
                            <img 
                                src="{{ asset($sql->imagem_tela) }}"
                                class="card-img-top"
                                alt="Imagem de capa do card"
                                style="max-width: 100px; opacity: 0.7; transition: opacity .2s ease"
                                >
                            <div class="card-body">
                                <h5 class="card-title" style="color: #495057">{{$sql->nome}}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-layout>


