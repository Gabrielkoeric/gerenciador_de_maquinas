<form action="{{$action}}" method="post">
    @csrf
    @isset($dadosAtuais->nome)
        @method('PUT')
    @endisset

    <div class="mb-3">
        <label for="nome" class=form-label>Nome:</label>
        <input type="text" id="nome" name="nome" class="form-control" @isset($dadosAtuais->nome) value="{{$dadosAtuais->nome}}" @endisset required>

        <label for="porta" class=form-label>Porta</label>
        <input type="porta" id="porta" name="porta" class="form-control" @isset($dadosAtuais->porta) value="{{$dadosAtuais->porta}}" @endisset required>

        <label for="vm" class=form-label>VM</label>
        <select id="vm" name="vm" class="form-control" required>
            @if(!isset($dadosAtuais))
            <option value="">Selecione a VM para o Serviço</option>
            @endif
            @if(isset($dadosAtuais))
                <option value="{{ $dadosAtuais->id_vm }}">{{ $dadosAtuais->nome_vm }}</option>
            @endif
            @foreach ($vms as $vm)

                    @if (isset($dadosAtuais))
                        @if ($vm->id_vm <> $dadosAtuais->id_vm)
                            <option value="{{ $vm->id_vm }}">{{ $vm->nome }}</option>
                        @endif
                    @else
                        <option value="{{ $vm->id_vm }}">{{ $vm->nome }}</option>
                    @endif
            @endforeach
        </select >

        <label for="servico" class=form-label>Serviço</label>
        <select id="servico" name="servico" class="form-control" required>
            @if(!isset($dadosAtuais))
            <option value="">Selecione o Serviço</option>
            @endif
            @if(isset($dadosAtuais))
                <option value="{{ $dadosAtuais->id_servico }}">{{ $dadosAtuais->nome_servico }}</option>
            @endif
            @foreach ($servicos as $servico)

                    @if (isset($dadosAtuais))
                        @if ($servico->id_servico <> $dadosAtuais->id_servico)
                            <option value="{{ $servico->id_servico }}">{{ $servico->nome }}</option>
                        @endif
                    @else
                        <option value="{{ $servico->id_servico }}">{{ $servico->nome }}</option>
                    @endif
            @endforeach
        </select >

        <label for="cliente" class=form-label>Cliente</label>
        <select id="cliente" name="cliente" class="form-control" required>
            @if(!isset($dadosAtuais))
            <option value="">Selecione o Cliente</option>
            @endif
            @if(isset($dadosAtuais))
                <option value="{{ $dadosAtuais->id_cliente_escala }}">{{ $dadosAtuais->nome_cliente }}</option>
            @endif
            @foreach ($clientes as $cliente)

                    @if (isset($dadosAtuais))
                        @if ($cliente->id_cliente_escala <> $dadosAtuais->id_cliente_escala)
                            <option value="{{ $cliente->id_cliente_escala }}">{{ $cliente->nome }}</option>
                        @endif
                    @else
                        <option value="{{ $cliente->id_cliente_escala }}">{{ $cliente->nome }}</option>
                    @endif
            @endforeach
        </select >
        
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{route('vm_servico.index')}}" class="btn btn-primary">Cancelar</a>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</form>
