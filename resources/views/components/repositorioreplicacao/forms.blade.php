<form action="{{$action}}" method="post">
    @csrf
    @isset($nome)
        @method('PUT')
    @endisset

    <div class="mb-3">

        <label for="tipo" class="form-label">Tipo de Movimentação:</label>
        <select id="tipo" name="tipo" class="form-control" required>
            <option value="">Selecione o Tipo de Movimentação</option>
            <option value="copy" @isset($tipo) @if($tipo == 'copy') selected @endif @endisset>Copy</option>
            <option value="move" @isset($tipo) @if($tipo == 'move') selected @endif @endisset>Move</option>
        </select>

        <label for="cliente" class=form-label>Cliente</label>
        <select id="cliente" name="cliente" class="form-control" required>
            @if(!isset($clienteAtual))
            <option value="">Selecione o Cliente</option>
            @endif
            @if(isset($clienteAtual))
                <option value="{{ $clienteAtual->id_cliente_escala }}">{{ $clienteAtual->apelido }}</option>
            @endif
            @foreach ($clientes as $cliente)

                    @if (isset($clienteAtual))
                        @if ($cliente->id_cliente_escala <> $clienteAtual->id_cliente_escala)
                            <option value="{{ $cliente->id_cliente_escala }}">{{ $cliente->apelido }}</option>
                        @endif
                    @else
                        <option value="{{ $cliente->id_cliente_escala }}">{{ $cliente->apelido }}</option>
                    @endif
            @endforeach
        </select >

        <label for="origem" class="form-label">Origem:</label>
        <input type="text" id="origem" name="origem" class="form-control" @isset($origem) value="{{$origem}}" @endisset required>

        <label for="destino" class="form-label">Destino:</label>
        <input type="text" id="destino" name="destino" class="form-control" @isset($destino) value="{{$destino}}" @endisset>

        <label for="log" class="form-label">Log:</label>
        <input type="text" id="log" name="log" class="form-control" @isset($log) value="{{$log}}" @endisset>

        <label for="tag" class="form-label">Tag:</label>
        <input type="text" id="tag" name="tag" class="form-control" @isset($tag) value="{{$tag}}" @endisset required>

        <label for="idServerBkp" class="form-label">Id Server BKP:</label>
        <input type="text" id="idServerBkp" name="idServerBkp" class="form-control" @isset($idServerBkp) value="{{$idServerBkp}}" @endisset required>

    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('repositorioreplicacao.index')}}" class="btn btn-primary">Cancelar</a>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
</form>