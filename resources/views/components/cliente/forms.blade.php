<form action="{{$action}}" method="post">
    @csrf
    @isset($nome)
        @method('PUT')
    @endisset

    <div class="mb-3">
        <label for="nome" class=form-label>Nome:</label>
        <input type="text" id="nome" name="nome" class="form-control" @isset($nome) value="{{$nome}}" @endisset required>

        <label for="apelido" class=form-label>Apelido</label>
        <input type="text" id="apelido" name="apelido" class="form-control" @isset($apelido) value="{{$apelido}}" @endisset required>

        <label for="porta" class=form-label>Porta RDP</label>
        <input type="text" id="porta" name="porta" class="form-control" @isset($porta) value="{{$porta}}" @endisset required>

        <label for="licenca" class=form-label>Licenca</label>
        <input type="text" id="licenca" name="licenca" class="form-control" @isset($licenca) value="{{$licenca}}" @endisset required>

        <label for="coletor" class=form-label>Coletor</label>
        <input type="text" id="coletor" name="coletor" class="form-control" @isset($coletor) value="{{$coletor}}" @endisset required>

        <label for="desktop" class=form-label>Desktop</label>
        <input type="text" id="desktop" name="desktop" class="form-control" @isset($desktop) value="{{$desktop}}" @endisset required>

        <label for="ativo" class=form-label>Ativo:</label><br>
        <input type="checkbox" class="form-control-input" id="ativo" name="ativo" value="1"
        @isset($ativo) @if($ativo) checked @endif @else checked @endisset>

    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('cliente_escala.index')}}" class="btn btn-primary">Cancelar</a>
</form>
