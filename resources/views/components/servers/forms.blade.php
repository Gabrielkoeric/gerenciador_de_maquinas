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

        <label for="ip_wan" class="form-label">IP WAN:</label>
        <input type="text" id="ip_wan" name="ip_wan" class="form-control" @isset($ip_wan) value="{{$ip_wan}}" @endisset>

        <label for="ip_lan" class="form-label">IP LAN:</label>
        <input type="text" id="ip_lan" name="ip_lan" class="form-control" @isset($ip_lan) value="{{$ip_lan}}" @endisset required>

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
        <input type="password" id="senha" name="senha" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('server.index')}}" class="btn btn-primary">Cancelar</a>
    
</form>
