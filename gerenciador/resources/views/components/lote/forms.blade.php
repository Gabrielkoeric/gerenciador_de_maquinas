<form action="{{$action}}" method="post" enctype="multipart/form-data">
    @csrf
    @isset($numeroLote)
        @method('PUT')
    @endisset
    <div class="mb-3">
        <label for="numeroLote" class="form-label">Numero Lote:</label>
        <input type="text" id="numeroLote" name="numeroLote" class="form-control" @isset($numeroLote) value="{{$numeroLote}}" @endisset required>

        <label for="quantidadeLote" class="form-label">Quantidade Lote</label>
        <input type="number" id="quantidadeLote" name="quantidadeLote" class="form-control" @isset($quantidadeLote) value="{{$quantidadeLote}}" @endisset required>

        <label for="quantidadeLoteDisponivel" class="form-label">Quantidade Lote Disponivel</label>
        <input type="number" id="quantidadeLoteDisponivel" name="quantidadeLoteDisponivel" class="form-control" @isset($quantidadeLoteDisponivel) value="{{$quantidadeLoteDisponivel}}" @endisset required>

        <label for="adicionalLote" class="form-label">Valor Adicional Lote</label>
        <input type="number" id="adicionalLote" name="adicionalLote" class="form-control" step="0.01" @isset($adicionalLote) value="{{$adicionalLote}}" @endisset required>

        <div class="form-check">
            <input type="checkbox" id="ativo" name="ativo" class="form-check-input" @isset($ativo) checked @endisset>
            <label for="ativo" class="form-check-label">Permitir Venda</label>
        </div>

        <label for="ingresso" class="form-label">Ingresso</label>
        <select id="ingresso" name="ingresso" class="form-control" required>
            @if(!isset($ingressoAtual))
                <option value="">Selecione o ingresso</option>
            @endif
            @if(isset($ingressoAtual))
                <option value="{{ $ingressoAtual->id_ingressos }}">{{ $ingressoAtual->nome }}</option>
                @endif
            @foreach ($ingressos as $ingresso)
                    @if (isset($ingressoAtual))
                        @if ($ingresso->id_ingressos <> $ingressoAtual->id_ingressos)
                            <option value="{{ $ingresso->id_ingressos }}">{{ $ingresso->nome }}</option>
                        @endif
                    @else
                        <option value="{{ $ingresso->id_ingressos }}">{{ $ingresso->nome }}</option>
                    @endif
            @endforeach
        </select>

















    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('lote.index')}}" class="btn btn-danger">Cancelar</a>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</form>
