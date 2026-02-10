<x-layout title="Resumo">
    <a href="{{ route('home.index') }}" class="btn btn-dark my-3">Home</a>

    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-center">

            <x-filtro-multi-select
                titulo="Serviços"
                name="servicos[]"
                :items="$todosServicos"
                id-field="id_servico"
                label-field="nome"
                :selecionados="$filtroServicos"
                lista-id="servicos-list"
            />

            <x-filtro-multi-select
                titulo="VMs"
                name="vms[]"
                :items="$todasVms"
                id-field="id_vm"
                label-field="nome"
                :selecionados="$filtroVms"
                lista-id="vms-list"
            />

            <x-filtro-multi-select
                titulo="Clientes"
                name="clientes[]"
                :items="$todosClientes"
                id-field="id_cliente_escala"
                label-field="nome"
                :selecionados="$filtroClientes"
                lista-id="clientes-list"
            />

            @if(count($filtroServicos) || count($filtroVms) || count($filtroClientes))
                <div class="col-auto">
                    <a href="{{ route('resumo.index') }}" class="btn btn-outline-danger">
                        <i class="fas fa-times"></i> Limpar filtros
                    </a>
                </div>
            @endif

        </div>
    </form>
    
    <style>
.table-wrap {
    overflow-x: hidden; /* remove scroll nativo */
    overflow-y: visible;
    -webkit-overflow-scrolling: touch;
}




        .sticky-col {
            position: sticky;
            left: 0;
            background-color: #f8f9fa;
            z-index: 2;
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: #e9ecef;
            z-index: 3;
        }

        table th, table td {
            white-space: nowrap;
            vertical-align: middle;
        }

        table.table td, table.table th {
            padding: .45rem .6rem;
        }

        /* scroll fixo no rodapé */
.scroll-footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;

    height: 18px;
    overflow-x: auto;
    overflow-y: hidden;

    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    z-index: 9999;
}

.scroll-footer-inner {
    height: 1px;
}


        /* remove aparência da tabela duplicada */
        .scroll-footer table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>

    <div class="table-wrap" id="table-wrap">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="sticky-col">Cliente</th>
                    @php
                        $servicos = $dados->pluck('servico')->unique()->filter();
                    @endphp
                    @foreach ($servicos as $servico)
                        <th colspan="4" class="text-center">{{ $servico }}</th>
                    @endforeach
                </tr>
                <tr>
                    <th class="sticky-col"></th>
                    @foreach ($servicos as $servico)
                        <th>Serviço</th>
                        <th>VM</th>
                        <th>IP</th>
                        <th>Porta</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($dados->groupBy('cliente') as $clienteNome => $itens)
                    <tr class="{{ $loop->index % 2 == 0 ? 'table-light' : 'table-secondary' }}">
                        <td class="sticky-col">{{ $clienteNome }}</td>
                        @foreach ($servicos as $servico)
                            @php
                                $registro = $itens->firstWhere('servico', $servico);
                            @endphp
                            <td>{{ $registro->nome_servico_vm ?? '' }}</td>
                            <td>{{ $registro->vm_nome ?? '' }}</td>
                            <td>{{ $registro->vm_ip ?? '' }}</td>
                            <td>{{ $registro->porta ?? '' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<div class="scroll-footer" id="scroll-footer">
    <div class="scroll-footer-inner" id="scroll-footer-inner">&nbsp;</div>
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
