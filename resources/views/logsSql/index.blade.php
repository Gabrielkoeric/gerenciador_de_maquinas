<x-layout title="Logs de Sql">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <form action="{{ route('logs_sql.clear') }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger my-3 pr"
                onclick="return confirm('Tem certeza que deseja apagar todos os logs?')">
            Limpar Logs
        </button>
    </form>

    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">ID Rotina</th>
                    <th scope="col">ID Ação</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">SQL</th>
                    <th scope="col">SQL Full</th>
                    <th scope="col">Tempo</th>
                    <th scope="col">Connection</th>
                    <th scope="col">Database</th>
                    <th scope="col">URL</th>
                    <th scope="col">Rota</th>
                    <th scope="col">Metodo HTTP</th>
                    <th scope="col">IP</th>
                    <th scope="col">Controller</th>
                    <th scope="col">Executado em</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($logsSql as $logSql)
                    <tr>
                        <td>{{ $logSql->id_log_sql }}</td>
                        <td>{{ $logSql->id_rotina }}</td>
                        <td>{{ $logSql->id_acao }}</td>
                        <td>{{ $logSql->id }}</td>
                        <td>{{ $logSql->sql }}</td>
                        <td>{{ $logSql->sql_full }}</td>
                        <td>{{ $logSql->tempo_ms }}</td>
                        <td>{{ $logSql->connection }}</td>
                        <td>{{ $logSql->database }}</td>
                        <td>{{ $logSql->url }}</td>
                        <td>{{ $logSql->rota }}</td>
                        <td>{{ $logSql->metodo_http }}</td>
                        <td>{{ $logSql->ip }}</td>
                        <td>{{ $logSql->controller }}</td>
                        <td>{{ $logSql->executado_em }}</td>                        
                    </tr>
                @endforeach

                </tbody>
            </table>
    </ul>
</x-layout>