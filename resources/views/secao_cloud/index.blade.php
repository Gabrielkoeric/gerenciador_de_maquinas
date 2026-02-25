<x-layout title="Seções Cloud">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('secao_cloud.create')}}" class="btn btn-dark my-3">Adicionar</a>
    <a href="{{route('secao_cloud_lote.index')}}" class="btn btn-dark my-3 pr">Adicionar Usuarios em Lote</a>

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
                <a href="{{ route('secao_cloud.index') }}" class="btn btn-outline-danger">
                    <i class="fas fa-times"></i> Limpar Filtros
                </a>
            </div>
            @endif
        </div>
    </form>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset

    <ul class="list-group">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Senha</th>
                    <th scope="col">Coletor</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dados as $dado)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td><a href="{{ route('secao_cloud.edit', $dado->id_secao_cloud ) }}" class="text-decoration-none text-dark">{{ $dado->usuario }}</a></td>
                        <td>
                            <span class="text-dark" role="button" style="cursor: pointer;" onclick="copiarSenha('{{ $dado->senha }}', this)">
                                {{ $dado->senha }}
                            </span>
                        </td>
                        <td>{{ $dado->coletor}}</td>
                        <td><a href="{{ route('secao_cloud.edit', $dado->id_secao_cloud) }}" class="text-decoration-none text-dark">{{ $dado->nome_cliente }}</a></td>
                        <td class="d-flex gap-1">
                            <form action="{{ route('secao_cloud.destroy', $dado->id_secao_cloud) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            
                            <form action="{{ route('secao_cloud.resetar', $dado->id_secao_cloud) }}" method="POST" onsubmit="return confirm('Deseja resetar a senha?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </ul>

    <script>
    function copiarSenha(texto, elemento) {
        navigator.clipboard.writeText(texto).then(() => {
            const original = elemento.innerText;
            elemento.innerText = 'Copiado!';
            setTimeout(() => {
                elemento.innerText = original;
            }, 1000);
        }).catch(err => {
            alert('Erro ao copiar: ' + err);
        });
    }

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