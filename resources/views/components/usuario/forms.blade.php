<form action="{{$action}}" method="post">
    @csrf
    @isset($nome)
        @method('PUT')
    @endisset

    <div class="mb-3">
        <label for="nome" class=form-label>Nome:</label>
        <input type="text" id="nome" name="nome" class="form-control" @isset($nome) value="{{$nome}}" @endisset required>

        <label for="email" class=form-label>E-mail</label>
        <input type="email" id="email" name="email" class="form-control" @isset($email) value="{{$email}}" @endisset
        @isset($email) readonly @endisset required>

        <label for="celular" class=form-label>Celular</label>
        <input type="tel" id="celular" name="celular" class="form-control" @isset($celular) value="{{$celular}}" @endisset required>

        <label for="permissao" class=form-label>Permissão</label>
        <select id="perfil" name="perfil" class="form-control" required>
            @if(!isset($perfilAtual))
            <option value="">Selecione o Perfil para o Usuario</option>
            @endif
            @if(isset($perfilAtual))
                <option value="{{ $perfilAtual->id_perfil }}">{{ $perfilAtual->nome_perfil }}</option>
            @endif
            @foreach ($perfis as $perfil)

                    @if (isset($perfilAtual))
                        @if ($perfil->id_perfil <> $perfilAtual->id_perfil)
                            <option value="{{ $perfil->id_perfil }}">{{ $perfil->nome }}</option>
                        @endif
                    @else
                        <option value="{{ $perfil->id_perfil }}">{{ $perfil->nome }}</option>
                    @endif
            @endforeach
        </select >
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('usuario.index')}}" class="btn btn-primary">Cancelar</a>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</form>
