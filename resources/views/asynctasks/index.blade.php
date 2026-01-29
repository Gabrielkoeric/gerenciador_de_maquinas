<x-layout title="Async Task">
    <a href="{{ route('home.index') }}" class="btn btn-dark my-3 pr">Home</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Disparo</th>
                <th scope="col">Parametros</th>
                <th scope="col">Inicio</th>
                <th scope="col">Fim</th>
                <th scope="col">Status</th>
                <th scope="col">Log</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task->id_async_tasks }}</td>
                    <td>{{ $task->nome_async_tasks }}</td>
                    <td>{{ $task->horario_disparo }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="showInModal(`{{ addslashes($task->parametros) }}`)">Ver Parametros</button>
                    </td>
                    <td>{{ $task->horario_inicio }}</td>
                    <td>{{ $task->horario_fim }}</td>
                    <td>{{ $task->status }}</td>
                    <td>
                        <button 
                            class="btn btn-secondary btn-sm" 
                            onclick="showInModal(`{{ addslashes($task->log) }}`)"
                        >
                            Ver Log
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

            <div class="d-flex justify-content-center mt-4">
            {{ $tasks->links('pagination::bootstrap-4') }}
        </div>
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
