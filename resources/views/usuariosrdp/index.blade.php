<x-layout title="Usuarios RDP">

    <a href="{{ route('home.index') }}"
       class="btn btn-dark my-3">
        Home
    </a>

    <div class="row">

        @foreach($rdps as $rdp)

            <div class="col-md-6 col-lg-4 mb-4">

                <div class="card shadow">

                    <div class="card-header bg-primary text-white">
                        <strong>{{ $rdp->nome }}</strong>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <strong>Clientes:</strong>
                            {{ $rdp->total_clientes }}
                            <br>

                            <strong>Sessões:</strong>
                            {{ $rdp->total_secoes }}
                        </div>

                        <table class="table table-sm">

                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th class="text-end">Sessões</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($rdp->clientes as $cliente)

                                    <tr>
                                        <td>{{ $cliente->nome }}</td>
                                        <td class="text-end">
                                            {{ $cliente->total_secoes }}
                                        </td>
                                    </tr>

                                @endforeach

                            </tbody>

                            <tfoot>
                                <tr class="table-secondary">
                                    <th>Total</th>
                                    <th class="text-end">
                                        {{ $rdp->total_secoes }}
                                    </th>
                                </tr>
                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</x-layout>