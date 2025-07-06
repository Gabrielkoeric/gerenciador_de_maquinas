<x-layout title="Repositorios">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <!--<a href="{{route('cliente_escala.create')}}" class="btn btn-dark my-3">Adicionar</a>-->
    <a href="{{route('rclone.executa') }}" class="btn btn-dark my-3">Executar Rclone</a>
    
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Prioridade</th>
                    <th scope="col">Rclone</th>
                    <th scope="col">Tipo Copia</th>
                    <th scope="col">Origem</th>
                    <th scope="col">Destino</th>
                    <th scope="col">Log Dir</th>
                    <th scope="col">Tags</th>
                    <th scope="col">Ativo</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">VM</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($repositorios as $repositorio)
                    <tr>
                        <td><a href="{{ route('rclone.edit', $repositorio->id_repositorios ) }}" class="text-decoration-none text-dark">{{ $repositorio->id_repositorios }}</a></td>
                        <td>{{ $repositorio->nome }}</td>
                        <td>{{ $repositorio->tipo }}</td>
                        <td>{{ $repositorio->prioridade }}</td>
                        <td>{{ $repositorio->rclone }}</td>
                        <td>{{ $repositorio->tipo_copia }}</td>
                        <td>{{ $repositorio->origem }}</td>
                        <td>{{ $repositorio->destino }}</td>
                        <td>{{ $repositorio->log_dir }}</td>
                        <td>{{ $repositorio->tags }}</td>
                        <td>{{ $repositorio->ativo }}</td>
                        <td>{{ $repositorio->id_cliente_escala }}</td>
                        <td>{{ $repositorio->id_vm }}</td>   
                    </tr>
                @endforeach

                </tbody>
            </table>
    </ul>
</x-layout>



