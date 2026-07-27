<x-layout title="VM em Uso">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <div class="row">

@foreach($vms as $vm)

<div class="col-lg-3 col-md-4 col-sm-6 mb-4">

    <div class="card shadow h-100">

        <div class="card-header text-center
            {{ $vm->online ? 'bg-success text-white' : 'bg-danger text-white' }}">

            {{ $vm->nome }}

        </div>

        <div class="card-body text-center">

            @if($vm->online)

                <h2>{{ $vm->usuarios }}</h2>

                <small>Usuários</small>

                <hr>

                {{ $vm->clientes_online }} cliente(s)

            @else

                <h3>Sem utilização</h3>

            @endif

        </div>

    </div>

</div>

@endforeach

</div>
</x-layout>