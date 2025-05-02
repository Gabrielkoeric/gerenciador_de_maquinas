<x-layout title="Editar Config Geral '{{$config->nomeConfig}}'">
    <x-configgeral.forms :action="route('config_geral.update', $config->id_config_geral)"
                        :nomeConfig="$config->nomeConfig"
                        :valorConfig="$config->valorConfig"
    >
    </x-configgeral.forms>
</x-layout>