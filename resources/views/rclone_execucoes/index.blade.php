<x-layout title="Logs Rclone Execuções">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('rclone.index')}}" class="btn btn-dark my-3 pr">Repositórios</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Repo Nome</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Disparo</th>
                    <th scope="col">Inicio</th>
                    <th scope="col">Fim</th>
                    <th scope="col">Status</th>
                    <th scope="col">Tranfered</th>
                    <th scope="col">Checks</th>
                    <th scope="col">Bytes</th>
                    <th scope="col">Arq. de log</th>
                    <!--<th scope="col">Log Erro</th>-->
                </tr>
                </thead>
                <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->repositorioNome }}</td>
                        <td>{{ $log->clienteNome }}</td>
                        <td>{{ $log->tipo }}</td>
                        <td>{{ $log->disparo }}</td>
                        <td>{{ $log->inicio }}</td>
                        <td>{{ $log->fim }}</td>
                        <td>{{ $log->status }}</td>
                        <td>{{ $log->qtd_arquivos_transferidos }}</td>
                        <td>{{ $log->qtd_arquivos_check }}</td>
                        <td>{{ $log->bytes_transferidos }}</td>
                        <td>{{ $log->log_path }}</td>
                        <!--<td>{{ $log->erro }}</td>-->
                    </tr>
                @endforeach

                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $logs->links('pagination::bootstrap-4') }}
            </div>
    </ul>
</x-layout>



