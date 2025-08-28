<x-layout title="Acessos">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('acessos.create')}}" class="btn btn-dark my-3">Adicionar</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Link</th>
                    <th scope="col">User</th>
                    <th scope="col">Pass</th>
                    <th scope="col">Descrição</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($acessos as $acesso)
                    <tr>
                        <td>
                            <a href="{{ $acesso->link }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary">Link</a>
                        </td>

                        
                      <td>
                            <span class="text-dark" role="button" style="cursor: pointer;" onclick="copiarTexto('{{ $acesso->usuario }}', this)">
                                {{ $acesso->usuario }}
                            </span>
                        </td>

                        <!-- Senha copiável -->
                        <td>
                            <span class="text-dark" role="button" style="cursor: pointer;" onclick="copiarTexto('{{ $acesso->senha }}', this)">
                                {{ $acesso->senha }}
                            </span>
                        </td>
                        <td><a href="{{ route('acessos.edit', $acesso->id_acesso) }}" class="text-decoration-none text-dark">{{ $acesso->descricao }}</a></td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <!-- Script JS para copiar -->
<script>
        function copiarTexto(texto, elemento) {
            navigator.clipboard.writeText(texto).then(() => {
                const original = elemento.innerText;
                elemento.innerText = 'Copiado!';
                setTimeout(() => {
                    elemento.innerText = original;
                }, 1000);
            }).catch(err => {
                console.error("Erro ao copiar:", err);
            });
        }
    </script>
    </ul>
</x-layout>