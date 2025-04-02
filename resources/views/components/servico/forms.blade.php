<form action="{{$action}}" method="post">
    @csrf
    @isset($nome)
        @method('PUT')
    @endisset

    <div class="mb-3">
        <label for="nome" class=form-label>Nome:</label>
        <input type="text" id="nome" name="nome" class="form-control" @isset($nome) value="{{$nome}}" @endisset required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('servico.index')}}" class="btn btn-primary">Cancelar</a>
</form>
