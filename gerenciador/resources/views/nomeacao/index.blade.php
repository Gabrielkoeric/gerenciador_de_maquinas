<x-layout title="Nomeação de Ingressos">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('nomeacao.create')}}" class="btn btn-dark my-3">Nomear Ingressos</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Nº Ingresso</th>
                    <th scope="col">Tipo do Ingresso</th>
                    <th scope="col">Usuario</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($sqls as $sql)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $sql->nome_ingresso }}</td>
                        <td>{{ $sql->email_usuario }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>

    </ul>
</x-layout>



