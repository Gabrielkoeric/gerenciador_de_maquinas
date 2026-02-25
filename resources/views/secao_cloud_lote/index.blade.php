<x-layout title="Seções Cloud Lote">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>
    <a href="{{route('secao_cloud.index')}}" class="btn btn-dark my-3 pr">Seção Cloud</a>

    <ul class="list-group">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Apelido</th>
                    <th scope="col">Licenças</th>
                    <th scope="col">Coletores</th>
                    <th scope="col">Desktop</th>
                </tr>
                </thead>
<tbody>
@foreach ($clientes as $cliente)
<tr data-id="{{ $cliente->id_cliente_escala }}">
    
    <td>{{ $cliente->nome }}</td>
    <td>{{ $cliente->apelido }}</td>

    {{-- LICENÇA TOTAL --}}
    <td>
        <span class="licenca">{{ $cliente->licenca }}</span>
    </td>

    {{-- COLETOR --}}
    <td>
        <button class="btn btn-sm btn-danger menos" data-tipo="coletor">-</button>
        <span class="valor coletor">{{ $cliente->coletor }}</span>
        <button class="btn btn-sm btn-success mais" data-tipo="coletor">+</button>
    </td>

    {{-- DESKTOP --}}
    <td>
        <button class="btn btn-sm btn-danger menos" data-tipo="desktop">-</button>
        <span class="valor desktop">{{ $cliente->desktop }}</span>
        <button class="btn btn-sm btn-success mais" data-tipo="desktop">+</button>
    </td>

    <td>
        <button class="btn btn-primary btn-sm salvar d-none">
            Salvar
        </button>
    </td>

</tr>
@endforeach
</tbody>
            </table>
    </ul>

<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".mais, .menos").forEach(btn => {
        btn.addEventListener("click", function () {

            let row = this.closest("tr");
            let tipo = this.dataset.tipo;
            let span = row.querySelector("." + tipo);
            let valorAtual = parseInt(span.innerText);

            if (this.classList.contains("mais")) {
                valorAtual++;
            } else {
                if (valorAtual > 0) valorAtual--;
            }

            span.innerText = valorAtual;

            atualizarLicenca(row);
            mostrarSalvar(row);
        });
    });

    document.querySelectorAll(".salvar").forEach(btn => {
        btn.addEventListener("click", function () {

            let row = this.closest("tr");

            fetch(`/secao_cloud_lote/${row.dataset.id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    coletor: row.querySelector(".coletor").innerText,
                    desktop: row.querySelector(".desktop").innerText,
                    licenca: row.querySelector(".licenca").innerText
                })
            })
            .then(res => res.json())
            .then(() => {
                this.classList.add("d-none");
            });

        });
    });

    function atualizarLicenca(row) {
        let coletor = parseInt(row.querySelector(".coletor").innerText);
        let desktop = parseInt(row.querySelector(".desktop").innerText);
        row.querySelector(".licenca").innerText = coletor + desktop;
    }

    function mostrarSalvar(row) {
        row.querySelector(".salvar").classList.remove("d-none");
    }

});
</script>


</x-layout>



