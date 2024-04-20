<x-layout title="Scripts">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('script.create')}}" class="btn btn-dark my-3">Adicionar</a>

    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Conteudo</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($scripts as $script)
                    <tr>
                        <td><a href="{{ route('usuario.edit', $script->id_script ) }}" class="text-decoration-none text-dark">{{ $script->nome }}</a></td>
                        <td><a href="{{ route('usuario.edit', $script->id_script) }}" class="text-decoration-none text-dark">{{ $script->descricao }}</a></td>
                        <td><a href="{{ route('usuario.edit', $script->id_script) }}" class="text-decoration-none text-dark">{{ $script->conteudo }}</a></td>
                        <td>
                        <span class="d-flex">
                            <!--<form action="{{route('usuario.destroy', $script->id_script)}}" method="post" class="ms-2">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Excluir</button>
                            </form>-->
                        </span>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>

    </ul>
</x-layout>



