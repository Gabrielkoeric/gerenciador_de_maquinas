<form action="{{ $action }}" method="post">
    @csrf

    <div class="mb-3">
        <label for="numeroSd" class="form-label">Número SD:</label>
        <input
            type="text"
            id="numeroSd"
            name="numeroSd"
            class="form-control"
            value="{{ old('numeroSd') }}"
            required
        >

        <label for="email" class="form-label">E-mail do Usuário:</label>
        <select id="email" name="email" class="form-control" required>
            <option value="">Selecione o e-mail do usuário</option>

            @foreach ($usuarios as $usuario)
                <option
                    value="{{ $usuario->id }}"
                    {{ old('email') == $usuario->id ? 'selected' : '' }}
                >
                    {{ $usuario->email }}
                </option>
            @endforeach
        </select>

        <label for="cliente" class="form-label">Cliente:</label>
        <select id="cliente" name="cliente" class="form-control" required>
            <option value="">Selecione o cliente</option>

            @foreach ($clientes as $cliente)
                <option
                    value="{{ $cliente->id_cliente_escala }}"
                    {{ old('cliente') == $cliente->id_cliente_escala ? 'selected' : '' }}
                >
                    {{ $cliente->apelido }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ route('home.index') }}" class="btn btn-primary">Cancelar</a>
</form>