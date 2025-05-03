<x-layout title="VM">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('vm.create')}}" class="btn btn-dark my-3">Adicionar</a>
    <a href="{{route('logs_execucoes.index')}}" class="btn btn-dark my-3">Logs Execuçoes</a>

    <form id="vmForm" action="{{ route('vm.executar') }}" method="POST">
        @csrf
        <input type="hidden" name="acao" id="acaoInput">
        
        <button type="button" class="btn btn-dark my-3 acaoBtn" onclick="confirmAction('status')" disabled>Status</button>
        <button type="button" class="btn btn-dark my-3 acaoBtn" onclick="confirmAction('stop')" disabled>Parar</button>
        <button type="button" class="btn btn-dark my-3 acaoBtn" onclick="confirmAction('start')" disabled>Iniciar</button>
        <button type="button" class="btn btn-dark my-3 acaoBtn" onclick="confirmAction('restart')" disabled>Restart</button>

        <table class="table table-striped">
            <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th scope="col">Nome</th>
                <th scope="col">Usuario</th>
                <th scope="col">Senha</th>
                <th scope="col">Dominio</th>
                <th scope="col">IP Lan</th>                    
                <th scope="col">Porta</th>
                <th scope="col">Srv Fisico</th>
                <th scope="col">Tipo</th>
                <th scope="col">AutoStart</th>
                <th scope="col">Acesso</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($vms as $vm)
                <tr>
                    <td>
                        <input type="checkbox" name="vm[]" class="selectItem" value="{{ $vm->id_vm }}">
                    </td>
                    <td><a href="{{ route('vm.edit', $vm->id_vm ) }}" class="text-decoration-none text-dark">{{ $vm->nome }}</a></td>
                    <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->usuario }}</a></td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" onclick="copyToClipboard(this)" data-senha="{{ $vm->senha }}">Senha</button>
                    </td>
                    <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->id_dominio }}</a></td>
                    <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->ip_lan }}</a></td>
                    <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->porta }}</a></td>
                    <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->servidor_nome }}</a></td>
                    <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->tipo }}</a></td>
                    <td><a href="{{ route('vm.edit', $vm->id_vm) }}" class="text-decoration-none text-dark">{{ $vm->autostart }}</a></td>
                    <td>
                        @if ($vm->so === 'ssh')
                            <a href="{{ route('conecta.ssh', $vm->id_vm) }}" class="btn btn-primary btn-sm" onclick="event.stopPropagation();">SSH</a>
                        @elseif ($vm->so === 'rdp')
                            <button type="button" class="btn btn-success btn-sm" onclick="event.stopPropagation(); copyRDPCommand('{{ $vm->ip_lan }}', '{{ $vm->porta }}', '{{ $vm->usuario }}', '{{ $vm->senha }}')">RDP</button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </form>

    <script>
        document.getElementById('selectAll').addEventListener('change', function () {
            let checkboxes = document.querySelectorAll('.selectItem');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        function confirmAction(acao) {
            const selectedVMs = document.querySelectorAll('.selectItem:checked');
            if (selectedVMs.length === 0) {
                alert("Selecione ao menos uma VM.");
                return;
            }
            const confirmation = confirm(`Você está prestes a executar um ${acao} em uma VM. Deseja continuar?`);
            if (confirmation) {
                submeterFormulario(acao);
            }
        }

        function submeterFormulario(acao) {
            let form = document.getElementById('vmForm');
            document.getElementById('acaoInput').value = acao;
            form.submit();
        }
    </script>

    <script>
        function copyToClipboard(element) {
            const value = element.getAttribute('data-senha');
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(value)
                    .then(() => alert('Senha copiada para a área de transferência!'))
                    .catch(() => fallbackCopy(value));
            } else {
                fallbackCopy(value);
            }
        }

        function fallbackCopy(value) {
            const tempInput = document.createElement('textarea');
            tempInput.value = value;
            document.body.appendChild(tempInput);
            tempInput.focus();
            tempInput.select();
            try {
                document.execCommand('copy');
                alert('Senha copiada para a área de transferência!');
            } catch (err) {
                alert('Erro ao copiar a senha.');
            }
            document.body.removeChild(tempInput);
        }
    </script>

    <script>
        // Atualiza os botões quando checkboxes são marcados
        function updateActionButtons() {
            const anyChecked = document.querySelectorAll('.selectItem:checked').length > 0;
            document.querySelectorAll('.acaoBtn').forEach(btn => btn.disabled = !anyChecked);
        }

        document.getElementById('selectAll').addEventListener('change', function () {
            document.querySelectorAll('.selectItem').forEach(cb => {
                cb.checked = this.checked;
            });
            updateActionButtons();
        });

        document.querySelectorAll('.selectItem').forEach(cb => {
            cb.addEventListener('change', updateActionButtons);
        });
    </script>

</x-layout>
