<x-layout title="Logs de API">
    <a href="{{ route('home.index') }}" class="btn btn-dark my-3">Home</a>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Data e Hora</th>
                <th>Cliente</th>
                <th>UUID</th>
                <th>IP</th>
                <th>Método</th>
                <th>Rota</th>
                <th>Status</th>
                <th>Tempo (ms)</th>
                <th>Tamanho (bytes)</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($logsApi as $logApi)
                <tr>
                    <td>{{ $loop->index }}</td>

                    <td>{{ \Carbon\Carbon::parse($logApi->data_hora)->format('d/m/Y H:i:s') }}</td>

                    <td>
                        @if($logApi->apelido)
                            {{ $logApi->apelido }}
                        @else
                            <span class="text-muted">Cliente não encontrado</span>
                        @endif
                    </td>

                    <td>{{ $logApi->uuid }}</td>

                    <td>{{ $logApi->ip }}</td>

                    <td>{{ $logApi->metodo }}</td>

                    <td>
                        <small>{{ $logApi->rota }}</small>
                    </td>

                    <td>
                        @if($logApi->status == 200)
                            <span class="badge bg-success">{{ $logApi->status }}</span>
                        @elseif($logApi->status >= 400)
                            <span class="badge bg-danger">{{ $logApi->status }}</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ $logApi->status }}</span>
                        @endif
                    </td>

                    <td>{{ $logApi->tempo_ms }}</td>

                    <td>{{ number_format($logApi->tamanho_resposta, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>