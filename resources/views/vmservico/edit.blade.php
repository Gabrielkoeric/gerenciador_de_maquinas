<x-layout title="Editar Serviço da VM '{{$dadosAtuais->nome}}'">
    <x-vmservico.forms  :action="route('vm_servico.update', $dadosAtuais->id_servico_vm)"
                        :dadosAtuais="$dadosAtuais"
                        :vms="$vms"
                        :servicos="$servicos"
                        :clientes="$clientes"
    >
    </x-vmservico.forms>
</x-layout>