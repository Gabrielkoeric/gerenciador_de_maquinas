<x-layout title="IP">
    <a href="{{ route('home.index') }}" class="btn btn-dark my-3">Home</a>
    <a href="{{route('ip.create')}}" class="btn btn-dark my-3">Adicionar ip</a>
<!--começa aq-->
    <form method="GET" action="{{ route('ip.index') }}">

<br><div>Filtros</div>


        <!---------------------incidente--------------------------------------------------->

        <label for="incidente" class=form-label>Incidente</label>
        <select id="incidente" name="incidente" class="form-control" >

                <option value="">Selecione um Incidente</option>
            @foreach ($incidentes as $incidente)

                        <option value="{{ $incidente->id_incidente }}">{{ $incidente->nome_incidente }}</option>
            @endforeach
        </select >
        <!---------------------Cidade--------------------------------------------------->
        <label for="cidade" class=form-label>Cidade</label>
        <select id="cidade" name="cidade" class="form-control" >

            <option value="">Selecione uma cidade</option>
            @foreach ($cidades as $cidade)

                <option value="{{ $cidade->cidade }}">{{ $cidade->cidade }}</option>
            @endforeach
        </select >

        <!---------------------Continente--------------------------------------------------->
        <label for="continente" class=form-label>Continentes</label>
        <select id="continente" name="continente" class="form-control" >

            <option value="">Selecione um continente</option>
            @foreach ($continentes as $continente)

                <option value="{{ $continente->continente }}">{{ $continente->continente }}</option>
            @endforeach
        </select >

        <!---------------------Região--------------------------------------------------->
        <label for="regiao" class=form-label>Regiao</label>
        <select id="regiao" name="regiao" class="form-control" >

            <option value="">Selecione uma Região</option>
            @foreach ($regioes as $regiao)

                <option value="{{ $regiao->regiao }}">{{ $regiao->regiao }}</option>
            @endforeach
        </select >






        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>
    <!-- Adicione este trecho ao seu código HTML -->







    <!--ate aq-->
    <table class="table table-striped">
        {{$i = 0}}
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">IP</th>
            <th scope="col">cidade</th>
            <th scope="col">Continente</th>
            <th scope="col">regiao</th>
            <th scope="col">Empresa</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($dados as $dado)
            <tr>
                <th>{{$i= $i + 1}}</th>
                <td>{{$dado->ip}}</td>
                <td>{{$dado->cidade}}</td>
                <td>{{$dado->continente}}</td>
                <td>{{$dado->regiao}}</td>
                <td>{{$dado->empresa}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
    <div id="map" style="height: 400px; width: 100%;"></div>
    <body onload="initMap()">
</x-layout>










