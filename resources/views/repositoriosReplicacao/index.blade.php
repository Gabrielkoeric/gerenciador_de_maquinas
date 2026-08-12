<x-layout title="Replicação Rio do Sul">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('repositorioreplicacao.create')}}" class="btn btn-dark my-3">Nova Replicação</a>
    
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Sincronizado</th>
                    <th scope="col">Apelido</th>
                    <th scope="col">Origem</th>
                    <th scope="col">Destino</th>
                    <th scope="col">Logs</th>
                    <th scope="col">Diario</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($repositorios as $repositorio)
                    <tr>
                        <td class="text-center">
                            @if ($repositorio->sincronizado)
                                <span
                                    title="Sincronizado"
                                    style="display: inline-block; width: 14px; height: 14px; background-color: #28a745; border-radius: 50%;">
                                </span>
                            @else
                                <span
                                    title="Não sincronizado"
                                    style="display: inline-block; width: 14px; height: 14px; background-color: #dc3545; border-radius: 50%;">
                                </span>
                            @endif
                        </td>
                        <td>{{$repositorio->apelido}}</td>
                        <td>{{ $repositorio->origem }}</td>
                        <td>{{ $repositorio->destino }}</td>
                        <td>{{ $repositorio->log_dir }}</td>
                        <td>{{ $repositorio->diario }}</td>   
                    </tr>
                @endforeach

                </tbody>
            </table>
    </ul>
</x-layout>