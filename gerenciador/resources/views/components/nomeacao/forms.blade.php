<form action="{{$action}}" method="post">
    @csrf
    @isset($nome)
        @method('PUT')
    @endisset

    <div class="mb-3">
        <label for="email" class=form-label>E-mail do Usuario:</label>
        <input type="text" id="email" name="email" class="form-control" @isset($email) value="{{$email}}" @endisset required>

        <label for="ingresso" class="form-label">Ingresso</label>
        <select id="ingresso" name="ingresso" class="form-control" required>
            <option value="">Selecione o ingresso</option> <!-- Opção padrão -->
            @foreach ($sqls as $sql)
                <option value="{{ $sql->id_compra_ingresso }}">{{ $sql->nome_ingresso }}</option>
            @endforeach
        </select>

    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('nomeacao.index')}}" class="btn btn-primary">Cancelar</a>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</form>



