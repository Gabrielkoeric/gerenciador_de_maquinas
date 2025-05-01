<form action="{{$action}}" method="post">
    @csrf
    @isset($ip)
        @method('PUT')
    @endisset

    <div class="mb-3">

        <label for="ip" class=form-label>IP:</label>
        <input type="text" id="ip" name="ip" class="form-control" @isset($ip) value="{{$ip}}" @endisset required>

        <label for="mascara" class=form-label>Mascara:</label>
        <input type="text" id="mascara" name="mascara" class="form-control" @isset($mascara) value="{{$mascara}}" @endisset required>

        <label for="descricao" class=form-label>Descrição:</label>
        <input type="text" id="descricao" name="descricao" class="form-control" @isset($descricao) value="{{$descricao}}" @endisset required>

    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('config_geral.index')}}" class="btn btn-primary">Cancelar</a>

</form>
