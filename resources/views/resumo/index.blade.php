<x-layout title="Resumo">
    <a href="{{ route('home.index') }}" class="btn btn-dark my-3">Home</a>

    <form method="GET" class="mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-auto">
            <label for="servicos">Filtrar Serviços:</label>
            <select name="servicos[]" id="servicos" class="form-select" multiple>
                @foreach($todosServicos as $servico)
                    <option value="{{ $servico->id_servico }}" 
                        @if(in_array($servico->id_servico, $filtroServicos)) selected @endif>
                        {{ $servico->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-auto">
            <label for="vms">Filtrar VMs:</label>
            <select name="vms[]" id="vms" class="form-select" multiple>
                @foreach($todasVms as $vm)
                    <option value="{{ $vm->id_vm }}" 
                        @if(in_array($vm->id_vm, $filtroVms)) selected @endif>
                        {{ $vm->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-primary mt-4">Filtrar</button>
            <a href="{{ route('resumo.index') }}" class="btn btn-secondary mt-4">Limpar</a>
        </div>
    </div>
</form>
    
    <style>
        .table-wrap {
            overflow-x: auto;
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
            overflow-x: auto;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px; /* altura da barra de rolagem */
            background: #f8f9fa;
            z-index: 10;
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

    <!-- scroll proxy fixo no rodapé -->
    <div class="scroll-footer" id="scroll-footer">
        <div style="width:{{ $dados->count() * 200 }}px"></div>
    </div>

    <script>
        const tableWrap = document.getElementById('table-wrap');
        const scrollFooter = document.getElementById('scroll-footer');

        // sincroniza scroll horizontal
        scrollFooter.addEventListener('scroll', () => {
            tableWrap.scrollLeft = scrollFooter.scrollLeft;
        });

        tableWrap.addEventListener('scroll', () => {
            scrollFooter.scrollLeft = tableWrap.scrollLeft;
        });

        
    </script>
</x-layout>
