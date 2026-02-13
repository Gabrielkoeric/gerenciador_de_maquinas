<form action="{{$action}}" method="post">
    @csrf
    <div class="mb-3">

        <label for="cliente" class=form-label>Cliente</label>
        <select id="cliente" name="cliente" class="form-control" required>
    <option value="">Selecione o Cliente</option>
    @foreach ($clientes as $cliente)
        <option value="{{ $cliente->id_cliente_escala }}" data-apelido="{{ $cliente->apelido }}">
            {{ $cliente->nome }}
        </option>
    @endforeach
</select>
    </div>

    <div class="mb-3">
        
        <label for="vm" class=form-label>VM</label>
        <select id="vm" name="vm" class="form-control" required>
            
            <option value="">Selecione a VM</option>
            
            @foreach ($vms as $vm)
                <option value="{{ $vm->id_vm }}">{{ $vm->nome }} ({{ $vm->total_servicos }})</option>
            @endforeach
        </select >
    </div>

    <div class="mb-3">
        <label for="ultimaPorta" class=form-label>Porta:</label>
        <input type="text" id="ultimaPorta" name="ultimaPorta" class="form-control" $porta value="{{$ultimaPorta}}" required>
    </div>

    <div class="mb-3">
        <label for="nome" class=form-label>Nome Serviço:</label>
        <input type="text" id="nome" name="nome" class="form-control" @isset($nome) value="{{$nome}}" @endisset required>
    </div>
    <button type="submit" class="btn btn-primary">Fazer Deploy</button>
    <a href="{{route('deploy.index')}}" class="btn btn-primary">Cancelar</a>

    <script>
    document.getElementById('cliente').addEventListener('change', function () {
        let selectedOption = this.options[this.selectedIndex];
        let apelido = selectedOption.getAttribute('data-apelido');
        let inputNome = document.getElementById('nome');

        if (apelido) {
            inputNome.value = 'escala_ws_' + apelido;
        } else {
            inputNome.value = '';
        }
    });
</script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</form>
