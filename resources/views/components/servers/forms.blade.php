<form action="{{$action}}" method="post">
    @csrf
    @isset($nome)
        @method('PUT')
    @endisset

    <div class="mb-3">
    <label for="nome" class="form-label">Nome:</label>
        <input type="text" id="nome" name="nome" class="form-control" @isset($nome) value="{{$nome}}" @endisset required>

        <label for="ipwan" class=form-label>IP Wan</label>
        <select id="ipwan" name="ipwan" class="form-control" required>
            @if(!isset($ipwanAtual))
            <option value="">Selecione o IP Wan</option>
            @endif
            @if(isset($ipwanAtual))
                <option value="{{ $ipwanAtual->id_ip_wan }}">{{ $ipwanAtual->ip }}</option>
            @endif
            @foreach ($ipswan as $ipwan)

                    @if (isset($ipwanAtual))
                        @if ($ipwan->id_ip_wan <> $ipwanAtual->id_ip_wan)
                            <option value="{{ $ipwan->id_ip_wan }}">{{ $ipwan->ip }}</option>
                        @endif
                    @else
                        <option value="{{ $ipwan->id_ip_wan }}">{{ $ipwan->ip }}</option>
                    @endif
            @endforeach
        </select >

        <label for="iplan" class=form-label>IP Lan</label>
        <select id="iplan" name="iplan" class="form-control" required>
            @if(!isset($iplanAtual))
            <option value="">Selecione o IP Lan</option>
            @endif
            @if(isset($iplanAtual))
                <option value="{{ $iplanAtual->id_ip_lan }}">{{ $iplanAtual->ip }}</option>
            @endif
            @foreach ($ipslan as $iplan)

                    @if (isset($iplanAtual))
                        @if ($iplan->id_ip_lan <> $iplanAtual->id_ip_lan)
                            <option value="{{ $iplan->id_ip_lan }}">{{ $iplan->ip }}</option>
                        @endif
                    @else
                        <option value="{{ $iplan->id_ip_lan }}">{{ $iplan->ip }}</option>
                    @endif
            @endforeach
        </select >

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

        <label for="mac" class="form-label">MAC:</label>
        <input type="text" id="mac" name="mac" class="form-control" @isset($mac) value="{{$mac}}" @endisset required>

        <label for="serial" class="form-label">Serial:</label>
        <input type="text" id="serial" name="serial" class="form-control" @isset($serial) value="{{$serial}}" @endisset required>

        <label for="usuario" class="form-label">Usuário:</label>
        <input type="text" id="usuario" name="usuario" class="form-control" @isset($usuario) value="{{$usuario}}" @endisset required>

        <label for="senha" class="form-label">Senha:</label>
        <input type="text" id="senha" name="senha" class="form-control" @isset($senha) value="{{$senha}}" @endisset required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('server.index')}}" class="btn btn-primary">Cancelar</a>
    
</form>
