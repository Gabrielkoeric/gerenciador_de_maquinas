<x-layout title="Editar Comando '{{$comando->id_comando_execucao_remota}}'">
    <x-comando.forms :action="route('comando.update', $comando->id_comando_execucao_remota)"
                        :tipo="$comando->tipo"
                        :acao="$comando->acao"
                        :comando="$comando->comando"
    >
    </x-comando.forms>
</x-layout>