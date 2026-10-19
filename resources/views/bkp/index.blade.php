<x-layout title="Backups">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">Data</th>
                <th scope="col">Tipo</th>
                <th scope="col">Tamanho</th>
                <th scope="col">Cliente</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bkps as $bkp)
                <tr>
                    <td>{{ $bkp->objeto }}</td>
                    <td>{{ $bkp->data_backup }}</td>
                    <td>{{ $bkp->tipo }}</td>
                    <td>{{ $bkp->tamanho_bytes }}</td>
                    <td>{{ $bkp->apelido }}</td>
                    <td>
                        <a href="{{ route('bkp.download', ['id' => $bkp->id_bkp]) }}"
                           class="btn btn-primary btn-sm">
                            Download
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
            <div class="d-flex justify-content-center mt-4">
            {{ $bkps->links('pagination::bootstrap-4') }}
        </div>
</x-layout>



