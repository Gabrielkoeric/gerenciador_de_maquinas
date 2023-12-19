<x-layout title="Perfil de Usuarios">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <ul class="list-group">
        @php
            $currentPerfil = null;
        @endphp

        @foreach ($data as $row)
            @if ($currentPerfil != $row->perfil_nome)
                @if ($currentPerfil)
    </ul> <!-- Fecha o submenu anterior -->
    @endif

    <li class="list-group-item">
        <h4>Perfil: {{ $row->perfil_nome }}</h4>
        <ul>
            @php
                $currentPerfil = $row->perfil_nome;
            @endphp
            @endif

            <li>Tela: {{ $row->tela_nome }}</li>
            @endforeach

            @if ($currentPerfil)
        </ul>

        @endif
    </li>


</x-layout>



