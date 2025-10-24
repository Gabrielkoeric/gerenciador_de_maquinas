<x-layout title="Agendamentos">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('horarios_agendamentos.index') }}" class="btn btn-dark my-3">Horarios Agendamentos</a>
    <!--<a href="{{route('agendamentos.create')}}" class="btn btn-dark my-3">Adicionar</a>-->

    @isset($mensagemSucesso)
        <div class="alert alert-success">{{ $mensagemSucesso }}</div>
    @endisset
    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Comand</th>
                    <th scope="col">Ultima Execução</th>
                    <th scope="col">Proxima Execução</th>
                    <th scope="col">Status</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Executar Agora</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($agendamentos as $agendamento)
                    <tr>
                        <td><a href="{{ route('agendamentos.edit', $agendamento->id_agendamentos) }}" class="text-decoration-none text-dark">{{ $agendamento->name }}</a></td>
                        <td><a href="{{ route('agendamentos.edit', $agendamento->id_agendamentos) }}" class="text-decoration-none text-dark">{{ $agendamento->command }}</a></td>
                        <td><a href="{{ route('agendamentos.edit', $agendamento->id_agendamentos) }}" class="text-decoration-none text-dark">{{ $agendamento->last_run_at }}</a></td>
                        <td><a href="{{ route('agendamentos.edit', $agendamento->id_agendamentos) }}" class="text-decoration-none text-dark">{{ $agendamento->next_run_at }}</a></td>
                        <td><a href="{{ route('agendamentos.edit', $agendamento->id_agendamentos) }}" class="text-decoration-none text-dark">{{ $agendamento->active }}</a></td>
                        <td><a href="{{ route('agendamentos.edit', $agendamento->id_agendamentos) }}" class="text-decoration-none text-dark">{{ $agendamento->description }}</a></td>
                        <td>
                            <form action="{{ route('agendamentos.executar', $agendamento->id_agendamentos) }}" method="POST" onsubmit="return confirm('Executar este comando agora?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    Executar Agora
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
    </ul>
</x-layout>



