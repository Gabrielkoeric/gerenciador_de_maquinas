<x-layout title="Seções Cloud Lote">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('secao_cloud.index')}}" class="btn btn-dark my-3 pr">Seção Cloud</a>

    <form method="GET" action="{{ route('secao_cloud.enviaEmail') }}" class="d-inline">
    
    <!-- mantém os filtros -->
    @foreach(request()->input('clientes', []) as $cliente)
        <input type="hidden" name="clientes[]" value="{{ $cliente }}">
    @endforeach

    <input type="email" name="email" class="form-control d-inline w-auto" placeholder="Email destino" required>

<button type="submit" class="btn btn-primary"
    @if(count($filtroClientes) == 0) disabled @endif>
    <i class="fas fa-envelope"></i> Enviar PDF
</button>
</form>

    <!-- Novo Filtro com Dropdown -->
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="filtroDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-filter"></i> Filtrar por Clientes
                        @if(count($filtroClientes) > 0)
                            <span class="badge bg-primary ms-1">{{ count($filtroClientes) }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu p-3" style="min-width: 300px;" aria-labelledby="filtroDropdown">
                        <div class="mb-2">
                            <small class="text-muted">Selecione um ou mais clientes:</small>
                        </div>
                        
                        <!-- Campo de busca para filtrar a lista -->
                        <div class="mb-2">
                            <input type="text" class="form-control form-control-sm" id="searchClientes" placeholder="Buscar cliente..." onkeyup="filtrarClientes()">
                        </div>
                        
                        <div style="max-height: 200px; overflow-y: auto;" id="clientesList">
                            @foreach($todosClientes as $cliente)
                                <div class="form-check cliente-item">
                                    <input class="form-check-input" type="checkbox" name="clientes[]" value="{{ $cliente->id_cliente_escala }}" 
                                        id="cliente_{{ $cliente->id_cliente_escala }}"
                                        @if(in_array($cliente->id_cliente_escala, $filtroClientes)) checked @endif>
                                    <label class="form-check-label w-100" for="cliente_{{ $cliente->id_cliente_escala }}">
                                        {{ $cliente->nome }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="dropdown-divider my-2"></div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="limparSelecao()">
                                Limpar
                            </button>
                            <div>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fas fa-check"></i> Aplicar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botão Limpar Filtros (visível apenas quando há filtros aplicados) -->
            @if(count($filtroClientes) > 0)
            <div class="col-auto">
                <a href="{{ route('secao_cloud_lote.index') }}" class="btn btn-outline-danger">
                    <i class="fas fa-times"></i> Limpar Filtros
                </a>
            </div>
            @endif
        </div>
    </form>

    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Apelido</th>
                    <th scope="col">Licenças</th>
                    <th scope="col">Coletores</th>
                    <th scope="col">Desktop</th>
                </tr>
                </thead>
<tbody>
@foreach ($dados as $dado)
<tr data-id="{{ $dado->id_cliente_escala }}">
    <th scope="row">{{ $loop->iteration }}</th>
    <td>{{ $dado->nome }}</td>
    <td>{{ $dado->apelido }}</td>

    {{-- LICENÇA TOTAL --}}
    <td>
        <span class="licenca">{{ $dado->licenca }}</span>
    </td>

    {{-- COLETOR --}}
    <td>
        <button class="btn btn-sm btn-danger menos" data-tipo="coletor">-</button>
        <span class="valor coletor">{{ $dado->coletor }}</span>
        <button class="btn btn-sm btn-success mais" data-tipo="coletor">+</button>
    </td>

    {{-- DESKTOP --}}
    <td>
        <button class="btn btn-sm btn-danger menos" data-tipo="desktop">-</button>
        <span class="valor desktop">{{ $dado->desktop }}</span>
        <button class="btn btn-sm btn-success mais" data-tipo="desktop">+</button>
    </td>

    <td>
        <button class="btn btn-primary btn-sm salvar d-none">
            Salvar
        </button>
    </td>

</tr>
@endforeach
</tbody>
            </table>
    </ul>

<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".mais, .menos").forEach(btn => {
        btn.addEventListener("click", function () {

            let row = this.closest("tr");
            let tipo = this.dataset.tipo;
            let span = row.querySelector("." + tipo);
            let valorAtual = parseInt(span.innerText);

            if (this.classList.contains("mais")) {
                valorAtual++;
            } else {
                if (valorAtual > 0) valorAtual--;
            }

            span.innerText = valorAtual;

            atualizarLicenca(row);
            mostrarSalvar(row);
        });
    });

    document.querySelectorAll(".salvar").forEach(btn => {
        btn.addEventListener("click", function () {

            let row = this.closest("tr");

            fetch(`/secao_cloud_lote/${row.dataset.id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    coletor: row.querySelector(".coletor").innerText,
                    desktop: row.querySelector(".desktop").innerText,
                    licenca: row.querySelector(".licenca").innerText
                })
            })
            .then(res => res.json())
            .then(() => {
                this.classList.add("d-none");
            });

        });
    });

    function atualizarLicenca(row) {
        let coletor = parseInt(row.querySelector(".coletor").innerText);
        let desktop = parseInt(row.querySelector(".desktop").innerText);
        row.querySelector(".licenca").innerText = coletor + desktop;
    }

    function mostrarSalvar(row) {
        row.querySelector(".salvar").classList.remove("d-none");
    }

});
</script>

   <script>
    // Função para filtrar clientes na lista
    function filtrarClientes() {
        const input = document.getElementById('searchClientes');
        const filter = input.value.toLowerCase();
        const clientesList = document.getElementById('clientesList');
        const items = clientesList.getElementsByClassName('cliente-item');
        
        for (let i = 0; i < items.length; i++) {
            const label = items[i].getElementsByTagName('label')[0];
            const text = label.textContent || label.innerText;
            if (text.toLowerCase().indexOf(filter) > -1) {
                items[i].style.display = "";
            } else {
                items[i].style.display = "none";
            }
        }
    }

    // Função para limpar seleção de checkboxes
    function limparSelecao() {
        const checkboxes = document.querySelectorAll('input[name="clientes[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    // Manter dropdown aberto quando clicar dentro dele
    document.addEventListener('DOMContentLoaded', function() {
        const dropdown = document.querySelector('.dropdown');
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    </script>

    <!-- Adicionar Bootstrap Icons (opcional, mas recomendado) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Bootstrap JavaScript (necessário para o dropdown) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</x-layout>