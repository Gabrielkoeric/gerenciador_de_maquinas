<form action="{{$action}}" method="post">
    @csrf
    @isset($link)
        @method('PUT')
    @endisset

    <div class="mb-3">
        <label for="link" class=form-label>Link:</label>
        <input type="text" id="link" name="link" class="form-control" @isset($link) value="{{$link}}" @endisset required>

        <label for="usuario" class=form-label>User:</label>
        <input type="text" id="usuario" name="usuario" class="form-control" @isset($usuario) value="{{$usuario}}" @endisset required>

        <label for="senha" class=form-label>Pass:</label>
        <input type="text" id="senha" name="senha" class="form-control" @isset($senha) value="{{$senha}}" @endisset required>

        <label for="descricao" class=form-label>Descrição:</label>
        <input type="text" id="descricao" name="descricao" class="form-control" @isset($descricao) value="{{$descricao}}" @endisset required>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('acessos.index')}}" class="btn btn-primary">Cancelar</a>
</form>
