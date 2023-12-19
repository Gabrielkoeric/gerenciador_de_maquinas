<form action="{{$action}}" method="post">
    @csrf
    @isset($nome)
        @method('PUT')
    @endisset

    <div class="mb-3">
        <label for="valor" class="form-label">Valor:</label>
        <input type="number" id="valor" name="valor" class="form-control" step="0.01" required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('gera.index')}}" class="btn btn-primary">Cancelar</a>
</form>
