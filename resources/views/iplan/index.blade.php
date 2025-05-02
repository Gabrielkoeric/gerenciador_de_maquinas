<x-layout title="IP Lan">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">IP</th>
                    <th scope="col">Server</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($ips as $ip)
                    <tr>
                        <td>{{ $ip->id_ip_lan }}</td>
                        <td>{{ $ip->ip }}</td>
                        <td>{{ $ip->nomeServidor }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </ul>
</x-layout>