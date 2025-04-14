<x-layout title="Logs Execuções">
    <a href="{{ route('home.index') }}" class="btn btn-dark my-3">Home</a>
    <a href="{{ route('vm_servico.index') }}" class="btn btn-dark my-3">Serviços da VM</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset

    <ul class="list-group">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Ação</th>
                    <th scope="col">Playbook</th>
                    <th scope="col">Comando</th>
                    <th scope="col">Saída</th>
                    <th scope="col">Status</th>
                    <th scope="col">Erro</th>
                    <th scope="col">Data Execução</th>
                    <th scope="col">Usuário</th>
                    <th scope="col">Serviço</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->id_logs_execucoes }}</td>
                        <td>{{ $log->acao }}</td>
                        <td>{{ $log->playbook }}</td>

                        {{-- Comando --}}
                        <td>
                            @if (!empty($log->comando))
                                <button class="btn btn-sm btn-info ver-mais" data-conteudo="{{ htmlentities($log->comando) }}">
                                    Ver mais
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Saída --}}
                        <td>
                            @if (!empty($log->saida))
                                <button class="btn btn-sm btn-info ver-mais" data-conteudo="{{ htmlentities($log->saida) }}">
                                    Ver mais
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td>{{ $log->status }}</td>

                        {{-- Erro --}}
                        <td>
                            @if (!empty($log->erro))
                                <button class="btn btn-sm btn-danger ver-mais" data-conteudo="{{ htmlentities($log->erro) }}">
                                    Ver mais
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td>{{ $log->executado_em }}</td>
                        <td>{{ $log->nome_usuario }}</td>
                        <td>{{ $log->nome_servico }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links('pagination::bootstrap-4') }}
        </div>
    </ul>

    {{-- Script para botões de "Ver mais" --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const botoes = document.querySelectorAll('.ver-mais');

            botoes.forEach(botao => {
                botao.addEventListener('click', () => {
                    const conteudo = botao.getAttribute('data-conteudo');
                    const texto = decodeHTMLEntities(conteudo);
                    alert(texto);
                });
            });

            function decodeHTMLEntities(text) {
                const textarea = document.createElement('textarea');
                textarea.innerHTML = text;
                return textarea.value;
            }
        });
    </script>
</x-layout>
