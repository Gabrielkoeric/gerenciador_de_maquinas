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
                    <th scope="col">Comando</th>
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
                        <td>
                            <button class="btn btn-secondary btn-sm" onclick="showInModal(`{{ addslashes($log->comando_rclone) }}`)">Comando</button>
                        </td>
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

    <!-- Modal -->
<div id="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:9999;">
    <div style="background:white; padding:20px; border-radius:10px; width:80%; max-height:80%; overflow:auto;">
        <pre id="modal-content" style="white-space: pre-wrap;"></pre>
        <button onclick="closeModal()" class="btn btn-danger mt-3">Fechar</button>
    </div>
</div>

<script>
    function showInModal(content) {
        document.getElementById('modal-content').textContent = content;
        document.getElementById('modal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }
</script>

</x-layout>



