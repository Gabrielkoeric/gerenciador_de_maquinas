<x-layout title="Async Task">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <ul class="list-group">

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
                        <td>{{ $task->parametros }}</td>
                        <td>{{ $task->horario_inicio }}</td>
                        <td>{{ $task->horario_fim }}</td>
                        <td>{{ $task->status }}</td>
                        <td>{{ $task->log }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
    </ul>
</x-layout>