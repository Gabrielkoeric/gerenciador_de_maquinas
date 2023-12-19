<x-layout title="Painel">
    <a href="{{route('home.index')}}" class="btn btn-dark mb-2">Home</a>
    <!--<a href="{{route('cor.create')}}" class="btn btn-dark mb-2">Adicionar</a>-->

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">
        @foreach ($cores as $cor)
            <li class="list-group-item d-flex justify-content-between align-items-center">{{$cor->cores_nome}}
                <span class="d-flex">
                    <a href="{{ route('cor.edit', $cor) }}" class="btn btn-primary btn-sm">Alterar</a>
                    <form action="{{route('cor.destroy', $cor)}}" method="post" class="ms-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Excluir</button>
                    </form>
                </span>
            </li>
        @endforeach
    </ul>
</x-layout>
