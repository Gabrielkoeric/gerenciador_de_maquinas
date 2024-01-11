<x-layout title="CPU">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('cpu.create')}}" class="btn btn-dark my-3">Adicionar</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset

</x-layout>



