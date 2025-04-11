<x-layout title="Logs Execuções">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Ação</th>
                    <th scope="col">Playbook</th>
                    <th scope="col">Comando</th>
                    <th scope="col">Saida</th>
                    <th scope="col">Status</th>
                    <th scope="col">Erro</th>
                    <th scope="col">Data Execução</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Serviço</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->id_logs_execucoes }}</td>
                        <td>{{ $log->acao }}</td>
                        <td>{{ $log->playbook }}</td>
                        <td>{{ $log->comando }}</td>
                        <td>{{ $log->saida }}</td>
                        <td>{{ $log->status }}</td>
                        <td>{{ $log->erro }}</td>
                        <td>{{ $log->executado_em }}</td>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->id_servico_vm }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links('pagination::bootstrap-4') }}
        </div>
    </ul>
</x-layout>