<form action="{{$action}}" method="post">
    @csrf
    @isset($nome)
        @method('PUT')
    @endisset

    <div class="mb-3">
    <label for="nome" class="form-label">Nome:</label>
        <input type="text" id="nome" name="nome" class="form-control" @isset($nome) value="{{$nome}}" @endisset required>

        <label for="dns" class="form-label">DNS:</label>
        <input type="text" id="dns" name="dns" class="form-control" @isset($dns) value="{{$dns}}" @endisset>

        <label for="ipwan" class="form-label">IP WAN:</label>
        <input type="text" id="ipwan" name="ipwan" class="form-control" @isset($ipwan) value="{{$ipwan}}" @endisset>

        <label for="iplan" class="form-label">IP LAN:</label>
        <input type="text" id="iplan" name="iplan" class="form-control" @isset($iplan) value="{{$iplan}}" @endisset required>

        <label for="porta" class="form-label">Porta:</label>
        <input type="text" id="porta" name="porta" class="form-control" @isset($porta) value="{{$porta}}" @endisset required>

        <label for="dominio" class="form-label">Domínio:</label>
        <input type="text" id="dominio" name="dominio" class="form-control" @isset($dominio) value="{{$dominio}}" @endisset>

        <label for="tipo" class="form-label">Tipo:</label>
        <select id="tipo" name="tipo" class="form-control" required>
            <option value="">Selecione o Tipo</option>
            <option value="ssh" @isset($tipo) @if($tipo == 'ssh') selected @endif @endisset>SSH</option>
            <option value="rdp" @isset($tipo) @if($tipo == 'rdp') selected @endif @endisset>RDP</option>
        </select>

        <label for="usuario" class="form-label">Usuário:</label>
        <input type="text" id="usuario" name="usuario" class="form-control" @isset($usuario) value="{{$usuario}}" @endisset required>

        <label for="senha" class="form-label">Senha:</label>
        <input type="text" id="senha" name="senha" class="form-control" @isset($senha) value="{{$senha}}" @endisset required>

        <label for="servidor" class=form-label>Servidor Fisico:</label>
        <select id="servidor" name="servidor" class="form-control" required>
            @if(!isset($servidorAtual))
            <option value="">Selecione o Servidor Fisico</option>
            @endif
            @if(isset($servidorAtual))
                <option value="{{ $servidorAtual->id_servidor_fisico }}">{{ $servidorAtual->nome }}</option>
            @endif
            @foreach ($servidores as $servidor)

                    @if (isset($servidorAtual))
                        @if ($servidor->id_servidor_fisico <> $servidorAtual->id_servidor_fisico)
                            <option value="{{ $servidor->id_servidor_fisico }}">{{ $servidor->nome }}</option>
                        @endif
                    @else
                        <option value="{{ $servidor->id_servidor_fisico }}">{{ $servidor->nome }}</option>
                    @endif
            @endforeach
        </select >

    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('vm.index')}}" class="btn btn-primary">Cancelar</a>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
</form>
