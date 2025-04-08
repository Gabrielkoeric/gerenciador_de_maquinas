<x-layout title="Rota Logs">
    <a href="{{ route('home.index') }}" class="btn btn-dark my-3 pr">Home</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset

    <ul class="list-group">
        <table class="table table-striped">
            <thead>
                <tr>               
                    <th scope="col">ID</th>
                    <th scope="col">Rota</th>
                    <th scope="col">Metodo</th>
                    <th scope="col">URL Completa</th>
                    <th scope="col">IP</th>
                    <th scope="col">Data</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->id_rota_logs }}</td>
                        <td>{{ $log->rota }}</td>
                        <td>{{ $log->metodo }}</td>
                        <td style="max-width: 250px; word-break: break-all;">{{ $log->url_completa }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ $log->created_at }}</td>
                        <td>{{ $log->id }}</td>
                        <td>
                            @if(!empty($log->request_data) && $log->request_data !== '[]')
                                <button class="btn btn-sm btn-info"
                                        onclick="verRequest({{ json_encode($log->request_data) }})">
                                    Ver mais
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links('pagination::bootstrap-4') }}
        </div>
    </ul>

    <script>
        function verRequest(data) {
            try {
                const obj = JSON.parse(data);
                alert(JSON.stringify(obj, null, 2));
            } catch (e) {
                alert(data); // fallback se não for JSON válido
            }
        }
    </script>
</x-layout>
