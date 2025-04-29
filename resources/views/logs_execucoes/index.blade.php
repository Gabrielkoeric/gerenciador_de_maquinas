<x-layout title="Logs Execuções">
    <a href="{{ route('home.index') }}" class="btn btn-dark my-3">Home</a>
    <a href="{{ route('vm_servico.index') }}" class="btn btn-dark my-3">Serviços da VM</a>

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
                    <th scope="col">Saída</th>
                    <th scope="col">Status</th>
                    <th scope="col">Erro</th>
                    <th scope="col">Data Execução</th>
                    <th scope="col">Usuário</th>
                    <th scope="col">Serviço</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->id_logs_execucoes }}</td>
                        <td>{{ $log->acao }}</td>
                        <td>{{ $log->playbook }}</td>

                        {{-- Comando --}}
                        <td>
                            @if (!empty($log->comando))
                                <button class="btn btn-sm btn-info" onclick="showInModal(`{{ addslashes($log->comando) }}`)">
                                    Ver Comando
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Saída --}}
                        <td>
                            @if (!empty($log->saida))
                                <button class="btn btn-sm btn-primary" onclick="showInModal(`{{ addslashes($log->saida) }}`)">
                                    Ver Saída
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td>{{ $log->status }}</td>

                        {{-- Erro --}}
                        <td>
                            @if (!empty($log->erro))
                                <button class="btn btn-sm btn-danger" onclick="showInModal(`{{ addslashes($log->erro) }}`)">
                                    Ver Erro
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td>{{ $log->executado_em }}</td>
                        <td>{{ $log->nome_usuario }}</td>
                        <td>{{ $log->nome_servico }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links('pagination::bootstrap-4') }}
        </div>
    </ul>

    <!-- Modal Customizado -->
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
