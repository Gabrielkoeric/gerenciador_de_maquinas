<form action="{{$action}}" method="post">
    @csrf
    @isset($nomeConfig)
        @method('PUT')
    @endisset

    <div class="mb-3">

        <label for="nomeConfig" class=form-label>Nome Config:</label>
        <input type="text" id="nomeConfig" name="nomeConfig" class="form-control" @isset($nomeConfig) value="{{$nomeConfig}}" @endisset required>

        <label for="valorConfig" class=form-label>Valor Config:</label>
        <input type="text" id="valorConfig" name="valorConfig" class="form-control" @isset($valorConfig) value="{{$valorConfig}}" @endisset required>

    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('config_geral.index')}}" class="btn btn-primary">Cancelar</a>

</form>
