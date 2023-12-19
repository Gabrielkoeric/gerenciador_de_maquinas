<form action="{{$action}}" method="post" enctype="multipart/form-data">
    @csrf
    @isset($nome)
        @method('PUT')
    @endisset
    <div class="mb-3">
        <label for="nome" class="form-label">Nome:</label>
        <input type="text" id="nome" name="nome" class="form-control" @isset($nome) value="{{$nome}}" @endisset required>

        <label for="descricao" class="form-label">Descricao</label>
        <input type="text" id="descricao" name="descricao" class="form-control" @isset($descricao) value="{{$descricao}}" @endisset required>

        <label for="quantidade" class="form-label">Quantidade</label>
        <input type="number" id="quantidade" name="quantidade" class="form-control" @isset($quantidade) value="{{$quantidade}}" @endisset required>

        <label for="quantidadeDisponivel" class="form-label">Quantidade Disponivel</label>
        <input type="number" id="quantidadeDisponivel" name="quantidadeDisponivel" class="form-control" step="0.01" @isset($quantidadeDisponivel) value="{{$quantidadeDisponivel}}" @endisset required>

        <label for="valor" class="form-label">Valor</label>
        <input type="number" id="valor" name="valor" class="form-control" step="0.01" @isset($valor) value="{{$valor}}" @endisset required>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('ingressos.index')}}" class="btn btn-danger">Cancelar</a>
</form>
