<form action="{{$action}}" method="post">
    @csrf
    @isset($usuario)
        @method('PUT')
    @endisset

    <div class="mb-3">
        <label for="usuario" class=form-label>Usuario:</label>
        <input type="text" id="usuario" name="usuario" class="form-control" @isset($usuario) value="{{$usuario}}" @endisset required>

        <label for="senha" class="form-label">Senha:</label>
<div class="input-group mb-3">
    <input type="text" id="senha" name="senha" class="form-control" @isset($senha) value="{{$senha}}" @endisset required>
    <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button" onclick="gerarSenha()">Gerar</button>
    </div>
</div>

        <label for="cliente" class=form-label>Cliente</label>
        <select id="cliente" name="cliente" class="form-control" required>
            @if(!isset($clienteAtual))
            <option value="">Selecione o Cliente</option>
            @endif
            @if(isset($clienteAtual))
                <option value="{{ $clienteAtual->id_cliente_escala }}">{{ $clienteAtual->nome_cliente }}</option>
            @endif
            @if(isset($clienteAtual))
                <option value="{{ $clienteAtual->id_cliente_escala }}" selected>{{ $clienteAtual->nome_cliente }}</option>
            @endif

            @foreach ($clientes as $cliente)

                    @if (isset($clienteAtual))
                        @if ($cliente->id_cliente_escala <> $clienteAtual->id_cliente_escala)
                            <option value="{{ $cliente->id_cliente_escala }}">{{ $cliente->nome }}</option>
                        @endif
                    @else
                        <option value="{{ $cliente->id_cliente_escala }}">{{ $cliente->nome }}</option>
                    @endif
            @endforeach
        </select >
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('secao_cloud.index')}}" class="btn btn-primary">Cancelar</a>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
    function gerarSenha(tamanho = 8) {
        const caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=';
        let senha = '';
        for (let i = 0; i < tamanho; i++) {
            senha += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        }
        document.getElementById('senha').value = senha;
    }
</script>

</form>
