<div class="col-auto">
    <div class="dropdown">
        <button class="btn btn-outline-primary dropdown-toggle" type="button"
    data-toggle="dropdown">
            <i class="fas fa-filter"></i> {{ $titulo }}

            @if(count($selecionados) > 0)
                <span class="badge bg-primary ms-1">{{ count($selecionados) }}</span>
            @endif
        </button>

        <div class="dropdown-menu p-3" style="min-width:300px">
            <input type="text"
                class="form-control form-control-sm mb-2"
                placeholder="Buscar {{ strtolower($titulo) }}..."
                onkeyup="filtrarLista(this, '{{ $listaId }}')">

            <div id="{{ $listaId }}" style="max-height:200px; overflow-y:auto">
                @foreach($items as $item)
                    <div class="form-check item">
                        <input class="form-check-input"
                            type="checkbox"
                            name="{{ $name }}"
                            value="{{ $item->{$idField} }}"
                            @checked(in_array($item->{$idField}, $selecionados))>

                        <label class="form-check-label">
                            {{ $item->{$labelField} }}
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="dropdown-divider"></div>

            <div class="d-flex justify-content-between">
                <button type="button"
                    class="btn btn-sm btn-outline-secondary"
                    onclick="limparGrupo('{{ $name }}')">
                    Limpar
                </button>
                <button type="submit" class="btn btn-sm btn-primary">
                    Aplicar
                </button>
            </div>
        </div>
    </div>
</div>