<x-layout title="Replicação Rio do Sul">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('repositorioreplicacao.create')}}" class="btn btn-dark my-3">Nova Replicação</a>
    
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
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



