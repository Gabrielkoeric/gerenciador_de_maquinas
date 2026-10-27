<x-layout title="Logs Download Bkp">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <ul class="list-group">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Objeto</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Data e Hora</th>
                    <th scope="col">Ip</th>
                    <th scope="col">Usuario</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <th scope="row">{{ $logs->count() - $loop->iteration + 1 }}</th>
                        <td>{{ $log->objeto }}</td>
                        <td>{{ $log->nome_cliente }}</td>
                        <td>{{ $log->data_hora }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ $log->email }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
    </ul>
</x-layout>