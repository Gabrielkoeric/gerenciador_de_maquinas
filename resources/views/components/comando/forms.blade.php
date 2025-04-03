<form action="{{$action}}" method="post">
    @csrf
    @isset($acao)
        @method('PUT')
    @endisset

    <div class="mb-3">
    <label for="tipo" class="form-label">Tipo:</label>
        <select id="tipo" name="tipo" class="form-control" required>
            <option value="">Selecione o Tipo</option>
            <option value="ssh" @isset($tipo) @if($tipo == 'ssh') selected @endif @endisset>SSH</option>
            <option value="rdp" @isset($tipo) @if($tipo == 'rdp') selected @endif @endisset>RDP</option>
        </select>

        <label for="acao" class=form-label>Ação:</label>
        <input type="text" id="acao" name="acao" class="form-control" @isset($acao) value="{{$acao}}" @endisset required>

        <label for="comando" class=form-label>Comando:</label>
        <input type="text" id="comando" name="comando" class="form-control" @isset($comando) value="{{$comando}}" @endisset required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('comando.index')}}" class="btn btn-primary">Cancelar</a>
</form>
