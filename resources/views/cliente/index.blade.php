<x-layout title="Clientes">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('cliente.create')}}" class="btn btn-dark my-3">Adicionar</a>
    <a href="{{route('cliente_escala.buscar') }}" class="btn btn-dark my-3">Buscar</a>
    <a href="{{route('cliente_escala.gerardm') }}" class="btn btn-dark my-3">Baixar RDM</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Apelido</th>
                    <th scope="col">Porta RDP</th>
                    <th scope="col">Licença</th>
                    <th scope="col">Coletor</th>
                    <th scope="col">Desktop</th>
                    <th scope="col">Ativo</th>
                    <th scope="col">RemoteApp</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td><a href="{{ route('cliente.edit', $cliente->id_cliente_escala ) }}" class="text-decoration-none text-dark">{{ $cliente->id_cliente_escala }}</a></td>
                        <td><a href="{{ route('cliente.edit', $cliente->id_cliente_escala) }}" class="text-decoration-none text-dark">{{ $cliente->nome }}</a></td>
                        <td><a href="{{ route('cliente.edit', $cliente->id_cliente_escala) }}" class="text-decoration-none text-dark">{{ $cliente->apelido }}</a></td>
                        <td><a href="{{ route('cliente.edit', $cliente->id_cliente_escala) }}" class="text-decoration-none text-dark">{{ $cliente->porta_rdp }}</a></td>
                        <td><a href="{{ route('cliente.edit', $cliente->id_cliente_escala ) }}" class="text-decoration-none text-dark">{{ $cliente->licenca }}</a></td>
                        <td><a href="{{ route('cliente.edit', $cliente->id_cliente_escala) }}" class="text-decoration-none text-dark">{{ $cliente->coletor }}</a></td>
                        <td><a href="{{ route('cliente.edit', $cliente->id_cliente_escala ) }}" class="text-decoration-none text-dark">{{ $cliente->desktop }}</a></td>
                        <td><a href="{{ route('cliente.edit', $cliente->id_cliente_escala) }}" class="text-decoration-none text-dark">{{ $cliente->ativo }}</a></td>
                        <td>
                            @if($cliente->remoteapp)
                                <a href="{{ asset('storage/remoteapp/' . $cliente->remoteapp) }}" 
                                class="btn btn-sm btn-success" 
                                target="_blank" 
                                download>
                            Baixar RDP
                        </a>
                        @else
                        <span class="text-muted">Nenhum arquivo</span>
                            @endif
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
    </ul>
</x-layout>



