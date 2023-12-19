<x-layout title="Logs de Acessos">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Email</th>
                    <th scope="col">IP</th>
                    <th scope="col">Data e Hora</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($accessLogs as $accessLog)
                    <tr>
                        <td>{{ $accessLog->id_access_logs }}</td>
                        <td>{{ $accessLog->email }}</td>
                        <td>{{ $accessLog->ip_address }}</td>
                        <td>{{ $accessLog->created_at }}</td>
                    </tr>
                @endforeach


                </tbody>
            </table>
        {{ $accessLogs->links('pagination::bootstrap-4') }}

    </ul>
</x-layout>



