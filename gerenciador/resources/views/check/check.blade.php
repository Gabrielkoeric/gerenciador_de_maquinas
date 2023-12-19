<x-layout title="Check-in Check-out">
    <a href="{{ route('home.index') }}" class="btn btn-dark my-3 pr">Home</a>

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Nome do Usuario</th>
            <th scope="col">Tipo Ingresso</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($dados as $dado)
            <tr>
                <td>{{ $dado->nome_do_usuario }}</td>
                <td>{{ $dado->nome_do_ingresso }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @if ($dado->checkin)
        <form method="post" action="{{ route('check.checkin', ['hash' => $hash]) }}">
            @csrf
            <button type="submit" class="btn btn-primary">Liberar Check-in</button>
        </form>
    @endif

    @if ($dado->checkout)
        <form method="post" action="{{ route('check.checkout', ['hash' => $hash]) }}">
            @csrf
            <button type="submit" class="btn btn-primary">Liberar Check-out</button>
        </form>
    @endif

</x-layout>
