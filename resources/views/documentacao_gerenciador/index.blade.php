<x-layout title="Documentação Gerenciador">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('documentacao_gerenciador.atualizar')}}" class="btn btn-dark my-3">Atualizar Documentação</a>
    
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
    @endif
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-0">
                    Documentação automática das rotinas do sistema.
                </p>
            </div>

            @if($documentacaoGerada)
                <span class="badge bg-success">
                    Documentação disponível
                </span>
            @endif
        </div>

        @if(!$documentacaoGerada)

            <div class="alert alert-warning">
                A documentação ainda não foi gerada.
                Execute:
                <code>php artisan docs:generate</code>
            </div>

        @elseif($documentation->isEmpty())

            <div class="alert alert-info">
                Nenhuma rotina documentada foi encontrada.
            </div>

        @else

            <div class="alert alert-secondary">
                <strong>{{ $documentation->count() }}</strong>
                rotinas documentadas.
            </div>

            @foreach($documentation->groupBy('module') as $module => $items)

                <div class="card mb-4">

                    <div class="card-header">
                        <strong>{{ $module }}</strong>
                        <span class="text-muted">
                            ({{ $items->count() }} rotinas)
                        </span>
                    </div>

                    <div class="card-body">

                        @foreach($items as $item)

                            <div class="border-bottom pb-3 mb-3">

                                <h5 class="mb-2">
                                    {{ $item['description'] }}
                                </h5>

                                <div class="mb-2">
                                    <span class="badge bg-primary">
                                        {{ $item['http_method'] }}
                                    </span>

                                    <code>
                                        /{{ $item['uri'] }}
                                    </code>
                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <strong>Controller:</strong><br>

                                        <code>
                                            {{ $item['controller_name'] }}@{{ $item['method'] }}
                                        </code>
                                    </div>

                                    <div class="col-md-6">
                                        <strong>Rota:</strong><br>

                                        @if($item['name'])
                                            <code>
                                                {{ $item['name'] }}
                                            </code>
                                        @else
                                            <span class="text-muted">
                                                Sem nome
                                            </span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endforeach

        @endif

    </div>

</x-layout>
