<x-layout title="Permissão Usuario Bkp">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('permissao_bkp.create')}}" class="btn btn-dark my-3">Adicionar</a>

    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ano Referencia</th>
                    <th scope="col">Numero SD</th>
                    <th scope="col">Data Liberação</th>
                    <th scope="col">Usuario Adm.</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($permissoes as $permissão)
                    <tr>
                        <th scope="row">{{ $permissoes->count() - $loop->iteration + 1 }}</th>
                        <td>{{ $permissão->apelido }}</td>
                        <td>{{ $permissão->email_usuario }}</td>
                        <td>{{ $permissão->ano_referencia }}</td>
                        <td>{{ $permissão->numero_sd }}</td>                        
                        <td>{{ $permissão->data_liberacao }}</td>
                        <td>{{ $permissão->nome_liberador }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
    </ul>
</x-layout>



