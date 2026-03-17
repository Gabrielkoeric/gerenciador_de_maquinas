<x-layout title="Usuarios Logados">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Cliente</th>
                    <th scope="col">Data</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">Diferença</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($usuariosLogados as $usuarioLogado)
                    <tr>
                        <td>{{ $usuarioLogado->cliente_nome }}</td>
                        <td>{{ $usuarioLogado->data }}</td>
                        <td>{{ $usuarioLogado->quantidade_atual  }}</td>
                        <td>
                            @php
                                $diff = $usuarioLogado->diferenca;
                            @endphp

                            @if ($diff > 0)
                                <span class="text-success fw-bold">
                                    <i class="bi bi-arrow-up-circle-fill"></i> +{{ $diff }}
                                </span>
                            @elseif ($diff < 0)
                                <span class="text-danger fw-bold">
                                    <i class="bi bi-arrow-down-circle-fill"></i> {{ $diff }}
                                </span>
                            @else
                                <span class="text-secondary fw-bold">
                                    <i class="bi bi-dash-circle-fill"></i> 0
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </ul>
</x-layout>



