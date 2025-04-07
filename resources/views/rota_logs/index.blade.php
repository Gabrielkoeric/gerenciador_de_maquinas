<x-layout title="Rota Logs">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

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
                    <th scope="col">User Agent</th>
                    <th scope="col">Request</th>
                    <th scope="col">Usuario</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->id_rota_logs }}</td>
                            <td>{{ $log->rota }}</td>
                            <td>{{ $log->metodo }}</td>
                            <td>{{ $log->url_completa }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->user_agent }}</td>
                            <td>{{ $log->request_data }}</td>
                            <td>{{ $log->id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </ul>
</x-layout>



