<x-layout title="HD">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a> 

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset

</x-layout>



