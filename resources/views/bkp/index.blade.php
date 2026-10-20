<x-layout title="Backups">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

<form method="GET" class="mb-4">
        <div class="row g-2 align-items-center">

            <x-filtro-multi-select
                titulo="Clientes"
                name="clientes[]"
                :items="$todosClientes"
                id-field="id_cliente_escala"
                label-field="nome"
                :selecionados="$filtroClientes"
                lista-id="clientes-list"
            />

            <x-filtro-multi-select
                titulo="Tipos"
                name="tipos[]"
                :items="$todosTipos"
                id-field="id"
                label-field="nome"
                :selecionados="$filtroTipos"
                lista-id="tipos-list"
            />

            @if(count($filtroClientes) || count($filtroTipos))
                <div class="col-auto">
                    <a href="{{ route('bkp.index') }}" class="btn btn-outline-danger">
                        <i class="fas fa-times"></i> Limpar filtros
                    </a>
                </div>
            @endif
        </div>
    </form>
    
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
                    <td>{{ number_format($bkp->tamanho_bytes / 1073741824, 2, ',', '.') }} GB</td>
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

    <script>
        const tableWrap = document.getElementById('table-wrap');
        const table = tableWrap.querySelector('table');
        const scrollFooter = document.getElementById('scroll-footer');
        const scrollFooterInner = document.getElementById('scroll-footer-inner');

        function syncScrollWidth() {
            const tableWidth = table.scrollWidth;
            const containerWidth = tableWrap.clientWidth;

            if (tableWidth > containerWidth) {
                scrollFooter.style.display = 'block';
                scrollFooterInner.style.width = tableWidth + 'px';
            } else {
                scrollFooter.style.display = 'none';
            }
        }

        // sincronização bidirecional
        scrollFooter.addEventListener('scroll', () => {
            tableWrap.scrollLeft = scrollFooter.scrollLeft;
        });

        tableWrap.addEventListener('scroll', () => {
            scrollFooter.scrollLeft = tableWrap.scrollLeft;
        });

        window.addEventListener('load', syncScrollWidth);
        window.addEventListener('resize', syncScrollWidth);
    </script>

</x-layout>