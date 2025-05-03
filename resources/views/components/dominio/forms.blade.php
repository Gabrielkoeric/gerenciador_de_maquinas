<form action="{{$action}}" method="post">
    @csrf
    @isset($nome)
        @method('PUT')
    @endisset

    <div class="mb-3">

        <label for="nome" class=form-label>Nome Dominio:</label>
        <input type="text" id="nome" name="nome" class="form-control" @isset($nome) value="{{$nome}}" @endisset required>

        <label for="usuario" class=form-label>Usuario:</label>
        <input type="text" id="usuario" name="usuario" class="form-control" @isset($usuario) value="{{$usuario}}" @endisset required>

        <label for="senha" class=form-label>Senha:</label>
        <input type="text" id="senha" name="senha" class="form-control" @isset($senha) value="{{$senha}}" @endisset required>

    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('dominios.index')}}" class="btn btn-primary">Cancelar</a>

</form>
