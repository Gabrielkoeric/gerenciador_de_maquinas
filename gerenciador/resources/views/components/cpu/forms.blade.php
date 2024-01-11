<form action="{{$action}}" method="post">
    @csrf
    @isset($marca)
        @method('PUT')
    @endisset

    <div class="mb-3">
        <label for="marca" class=form-label>Marca:</label>
        <input type="text" id="marca" name="marca" class="form-control" @isset($marca) value="{{$marca}}" @endisset required>

        <label for="modelo" class=form-label>Modelo:</label>
        <input type="text" id="modelo" name="modelo" class="form-control" @isset($marca) value="{{$marca}}" @endisset required>

        <label for="geracao" class=form-label>Geração:</label>
        <input type="text" id="geracao" name="geracao" class="form-control" @isset($marca) value="{{$marca}}" @endisset required>

    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('cpu.index')}}" class="btn btn-primary">Cancelar</a>
</form>
