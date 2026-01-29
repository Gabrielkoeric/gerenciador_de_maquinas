<x-layout title="Horarios Agendamentos">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('agendamentos.index') }}" class="btn btn-dark my-3">Agendamentos</a>

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Expression</th>
                    <th scope="col">Type</th>
                    <th scope="col">Meta</th>
                    <th scope="col">Active</th>
                    <th scope="col">Nome Agendamento</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($horarios as $horario)
                    <tr>
                        <td><a href="{{ route('horarios_agendamentos.edit', $horario->id_horarios_agendamentos) }}" class="text-decoration-none text-dark">{{ $horario->expression }}</a></td>
                        <td><a href="{{ route('horarios_agendamentos.edit', $horario->id_horarios_agendamentos) }}" class="text-decoration-none text-dark">{{ $horario->type }}</a></td>
                        <td><a href="{{ route('horarios_agendamentos.edit', $horario->id_horarios_agendamentos) }}" class="text-decoration-none text-dark">{{ $horario->meta }}</a></td>
                        <td><a href="{{ route('horarios_agendamentos.edit', $horario->id_horarios_agendamentos) }}" class="text-decoration-none text-dark">{{ $horario->active }}</a></td>
                        <td><a href="{{ route('horarios_agendamentos.edit', $horario->id_horarios_agendamentos) }}" class="text-decoration-none text-dark">{{ $horario->nome_agendamento }}</a></td>
                    </tr>
                @endforeach

                </tbody>
            </table>
    </ul>
</x-layout>



