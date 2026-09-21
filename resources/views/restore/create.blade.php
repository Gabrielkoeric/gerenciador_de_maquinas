<x-layout title="Novo Restore">

    <a href="{{ route('restore.index') }}" class="btn btn-dark my-3">Restore</a>

    <form
        method="POST"
        action="{{ route('restore.store') }}"
        id="restore-form"
    >

        @csrf

        <div class="card mb-4">
            <div class="card-header">
                <strong>Novo Restore</strong>
            </div>

            <div class="card-body">

                {{-- SERVIDOR --}}
                <div class="mb-3">
                    <label for="id_vm" class="form-label">
                        Servidor de Banco de Dados
                    </label>

                    <select name="id_vm" id="id_vm" class="form-select">
                        <option value="">Selecione o servidor</option>

                        @foreach ($servidores as $servidor)
                            <option value="{{ $servidor->id_vm }}">
                                {{ $servidor->nome }} -
                                {{ $servidor->ip_lan }} -
                                {{ $servidor->so }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- CLIENTE --}}
                <div class="mb-3">
                    <label for="id_cliente" class="form-label">
                        Cliente
                    </label>

                    <select name="id_cliente" id="id_cliente" class="form-select">
                        <option value="">Selecione o cliente</option>

                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id_cliente_escala }}">
                                {{ $cliente->apelido }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>


        {{-- FULL --}}
        <div id="full-container" class="mb-4"></div>


        {{-- DIFF --}}
        <div id="diff-container" class="mb-4"></div>


        {{-- LOG --}}
        <div id="log-container" class="mb-4"></div>


        {{-- SALVAR --}}
        <div
            id="salvar-container"
            class="mb-5"
            style="display:none;"
        >

            <button
                type="submit"
                class="btn btn-success"
            >
                Salvar Restore
            </button>

        </div>

    </form>


</x-layout>


<script>

let backups = null;
let fullSelecionado = null;
let diffSelecionado = null;
let logsSelecionados = [];
let restoreDataHora = null;


/*
|--------------------------------------------------------------------------
| CLIENTE SELECIONADO
|--------------------------------------------------------------------------
*/

document.getElementById('id_cliente').addEventListener('change', function () {

    const cliente = this.value;

    limparEtapas();

    if (!cliente) {
        return;
    }

    const url = "{{ route('restore.backups', ':cliente') }}"
        .replace(':cliente', cliente);


    fetch(url)
        .then(response => {

            if (!response.ok) {
                throw new Error('Erro ao buscar backups.');
            }

            return response.json();
        })
        .then(data => {

            console.log('Backups recebidos:', data);

            backups = data;

            mostrarFulls(data.full);

        })
        .catch(error => {

            console.error(error);

            alert('Erro ao carregar os backups.');
        });

});


/*
|--------------------------------------------------------------------------
| MOSTRAR FULL
|--------------------------------------------------------------------------
*/

function mostrarFulls(fulls)
{
    const container = document.getElementById('full-container');

    container.innerHTML = '';

    if (!fulls || fulls.length === 0) {

        container.innerHTML = `
            <div class="alert alert-warning">
                Nenhum backup FULL encontrado.
            </div>
        `;

        return;
    }


    let html = `
        <div class="card">

            <div class="card-header">
                <strong>1. Selecione o backup FULL</strong>
            </div>

            <div class="card-body">
    `;


    fulls.slice().reverse().forEach((full, index) => {

        const id = `full-${index}`;

        const tamanho = formatarTamanho(full.tamanho_total);

        html += `
            <div class="card mb-2">

                <div class="card-body">

                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="full"
                            id="${id}"
                        >

                        <label
                            class="form-check-label w-100"
                            for="${id}"
                            style="cursor:pointer"
                        >

                            <strong>
                                ${formatarData(full.data_hora)}
                            </strong>

                            <div class="text-muted small mt-1">

                                ${full.quantidade_partes} partes
                                • ${tamanho}

                            </div>

                        </label>

                    </div>

                </div>

            </div>
        `;

    });


    html += `
            </div>
        </div>
    `;


    container.innerHTML = html;


    /*
     * Eventos dos FULLs
     */

    fulls.slice().reverse().forEach((full, index) => {

        const radio = document.getElementById(`full-${index}`);

        radio.addEventListener('change', function () {

            fullSelecionado = full;

            diffSelecionado = null;

            logsSelecionados = [];

            restoreDataHora = null;

            document.getElementById('log-container').innerHTML = '';

            document.getElementById('salvar-container').style.display = 'none';

            mostrarDiffs(full);

        });

    });

}


/*
|--------------------------------------------------------------------------
| MOSTRAR DIFFS
|--------------------------------------------------------------------------
*/

function mostrarDiffs(full)
{
    const container = document.getElementById('diff-container');

    container.innerHTML = '';

    const diffs = full.diffs || [];


    if (diffs.length === 0) {

        container.innerHTML = `
            <div class="alert alert-warning">
                Nenhum backup DIFF encontrado para este FULL.
            </div>
        `;

        return;
    }


    let html = `
        <div class="card">

            <div class="card-header">
                <strong>2. Selecione o backup DIFF</strong>
            </div>

            <div class="card-body">
    `;


    diffs.forEach((diff, index) => {

        const id = `diff-${index}`;

        html += `
            <div class="card mb-2">

                <div class="card-body">

                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="diff"
                            id="${id}"
                        >

                        <label
                            class="form-check-label w-100"
                            for="${id}"
                            style="cursor:pointer"
                        >

                            <strong>
                                ${formatarData(diff.data_hora)}
                            </strong>

                            <div class="text-muted small mt-1">

                                ${formatarTamanho(diff.tamanho)}

                            </div>

                        </label>

                    </div>

                </div>

            </div>
        `;

    });


    html += `
            </div>
        </div>
    `;


    container.innerHTML = html;


    /*
     * Eventos dos DIFFs
     */

    diffs.forEach((diff, index) => {

        const radio = document.getElementById(`diff-${index}`);

        radio.addEventListener('change', function () {

            diffSelecionado = diff;

            logsSelecionados = [];

            restoreDataHora = null;

            document.getElementById('salvar-container').style.display = 'none';

            mostrarLogs(diff);

        });

    });

}


/*
|--------------------------------------------------------------------------
| MOSTRAR LOGS
|--------------------------------------------------------------------------
*/

function mostrarLogs(diff)
{
    const container = document.getElementById('log-container');

    container.innerHTML = '';

    const logs = diff.logs || [];


    if (logs.length === 0) {

        container.innerHTML = `
            <div class="alert alert-info">
                Nenhum LOG encontrado após este DIFF.
            </div>
        `;

        return;
    }


    let html = `
        <div class="card">

            <div class="card-header">
                <strong>3. Selecione o ponto de recuperação</strong>
            </div>

            <div class="card-body">

                <p class="text-muted">
                    DIFF selecionado:
                    <strong>${formatarData(diff.data_hora)}</strong>
                </p>


                <div class="list-group">
    `;


    /*
    |--------------------------------------------------------------------------
    | CHECKBOX DOS LOGS
    |--------------------------------------------------------------------------
    */

    logs.forEach((log, index) => {

        const id = `log-${index}`;

        html += `
            <label
                class="list-group-item list-group-item-action"
                style="cursor:pointer"
                for="${id}"
            >

                <input
                    class="form-check-input me-2 log-checkbox"
                    type="checkbox"
                    name="logs[]"
                    value="${index}"
                    id="${id}"
                    data-index="${index}"
                >

                ${formatarData(log.data_hora)}

            </label>
        `;

    });


    html += `
                </div>


                {{-- DATA/HORA MANUAL --}}

                <div class="mt-4">

                    <label class="form-label">
                        Ou informe a data e hora exata do restore
                    </label>

                    <div class="row">

                        <div class="col-md-6">

                            <label
                                for="restore-data"
                                class="form-label"
                            >
                                Data
                            </label>

                            <input
                                type="date"
                                id="restore-data"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-6">

                            <label
                                for="restore-hora"
                                class="form-label"
                            >
                                Hora
                            </label>

                            <input
                                type="time"
                                id="restore-hora"
                                class="form-control"
                                step="1"
                            >

                        </div>

                    </div>

                </div>


                {{-- CAMPO QUE VAI PARA O CONTROLLER --}}

                <input
                    type="hidden"
                    name="restore_data_hora"
                    id="restore_data_hora"
                >


                {{-- RESUMO --}}

                <div
                    id="logs-selecionados"
                    class="alert alert-secondary mt-3"
                >
                    Nenhum LOG selecionado.
                </div>

            </div>
        </div>
    `;


    container.innerHTML = html;


    /*
    |--------------------------------------------------------------------------
    | EVENTO DOS CHECKBOXES
    |--------------------------------------------------------------------------
    */

    logs.forEach((log, index) => {

        const checkbox =
            document.getElementById(`log-${index}`);


        checkbox.addEventListener('change', function () {

            const checkboxes =
                container.querySelectorAll(
                    '.log-checkbox'
                );


            /*
            |--------------------------------------------------------------------------
            | MARCOU
            |--------------------------------------------------------------------------
            |
            | Marca este LOG e todos os anteriores.
            |
            */

            if (this.checked) {

                checkboxes.forEach(
                    (item, itemIndex) => {

                        item.checked =
                            itemIndex <= index;

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | DESMARCOU
            |--------------------------------------------------------------------------
            |
            | Desmarca este LOG e todos os posteriores.
            |
            */

            else {

                checkboxes.forEach(
                    (item, itemIndex) => {

                        if (itemIndex >= index) {
                            item.checked = false;
                        }

                    }
                );

            }


            atualizarLogsSelecionados(logs);

        });

    });


    /*
    |--------------------------------------------------------------------------
    | DATA/HORA MANUAL
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('restore-data')
        .addEventListener('change', function () {

            aplicarDataHoraLogs(logs);

        });


    document
        .getElementById('restore-hora')
        .addEventListener('change', function () {

            aplicarDataHoraLogs(logs);

        });

}


/*
|--------------------------------------------------------------------------
| ATUALIZAR LOGS SELECIONADOS
|--------------------------------------------------------------------------
*/

function atualizarLogsSelecionados(logs)
{
    const checkboxes =
        document.querySelectorAll(
            '.log-checkbox'
        );


    logsSelecionados = [];


    checkboxes.forEach((checkbox, index) => {

        if (checkbox.checked) {

            logsSelecionados.push(
                logs[index]
            );

        }

    });


    const resumo =
        document.getElementById(
            'logs-selecionados'
        );


    if (logsSelecionados.length === 0) {

        resumo.className =
            'alert alert-secondary mt-3';

        resumo.innerHTML =
            'Nenhum LOG selecionado.';

        document.getElementById(
            'salvar-container'
        ).style.display = 'none';

        return;
    }


    const primeiro =
        logsSelecionados[0];

    const ultimo =
        logsSelecionados[
            logsSelecionados.length - 1
        ];


    resumo.className =
        'alert alert-success mt-3';


    resumo.innerHTML = `
        <strong>
            ${logsSelecionados.length}
            LOG(s) selecionado(s)
        </strong>

        <br>

        De:
        <strong>
            ${formatarData(primeiro.data_hora)}
        </strong>

        até:
        <strong>
            ${formatarData(ultimo.data_hora)}
        </strong>
    `;


    document.getElementById(
        'salvar-container'
    ).style.display = 'block';


    console.log(
        'LOGs selecionados:',
        logsSelecionados
    );

}


/*
|--------------------------------------------------------------------------
| APLICAR DATA/HORA MANUAL
|--------------------------------------------------------------------------
|
| IMPORTANTE:
|
| Se o usuário pedir 15:59 e os LOGs forem:
|
| 15:50
| 15:55
| 16:00
|
| precisamos usar também o LOG das 16:00.
|
| Por isso a comparação é:
|
| dataLog >= dataRestore
|
| para encontrar o PRIMEIRO LOG que seja posterior
| ao horário solicitado.
|
*/

function aplicarDataHoraLogs(logs)
{
    const data =
        document.getElementById(
            'restore-data'
        ).value;


    const hora =
        document.getElementById(
            'restore-hora'
        ).value;


    if (!data || !hora) {
        return;
    }


    const dataHoraInformada =
        new Date(`${data}T${hora}`);


    const checkboxes =
        document.querySelectorAll(
            '.log-checkbox'
        );


    let indiceUltimoLog = -1;


    /*
    |--------------------------------------------------------------------------
    | Procura o primeiro LOG que seja >= ao STOPAT
    |--------------------------------------------------------------------------
    */

    logs.forEach((log, index) => {

        const dataLog =
            new Date(
                log.data_hora.replace(' ', 'T')
            );


        if (
            indiceUltimoLog === -1 &&
            dataLog >= dataHoraInformada
        ) {

            indiceUltimoLog = index;

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Se encontrou o LOG que contém o horário
    |--------------------------------------------------------------------------
    */

    if (indiceUltimoLog !== -1) {

        checkboxes.forEach(
            (checkbox, index) => {

                checkbox.checked =
                    index <= indiceUltimoLog;

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Atualiza os LOGs
    |--------------------------------------------------------------------------
    */

    atualizarLogsSelecionados(logs);


    /*
    |--------------------------------------------------------------------------
    | Guarda o STOPAT exato
    |--------------------------------------------------------------------------
    */

    restoreDataHora =
        `${data} ${hora}`;


    document.getElementById(
        'restore_data_hora'
    ).value = restoreDataHora;


    console.log(
        'Restore solicitado até:',
        restoreDataHora
    );

}


/*
|--------------------------------------------------------------------------
| LIMPAR ETAPAS
|--------------------------------------------------------------------------
*/

function limparEtapas()
{
    document.getElementById('full-container').innerHTML = '';
    document.getElementById('diff-container').innerHTML = '';
    document.getElementById('log-container').innerHTML = '';

    document.getElementById(
        'salvar-container'
    ).style.display = 'none';

    backups = null;
    fullSelecionado = null;
    diffSelecionado = null;
    logsSelecionados = [];
    restoreDataHora = null;
}


/*
|--------------------------------------------------------------------------
| FORMATA DATA
|--------------------------------------------------------------------------
*/

function formatarData(data)
{
    if (!data) {
        return '-';
    }

    const d = new Date(data.replace(' ', 'T'));

    return d.toLocaleString('pt-BR');
}


/*
|--------------------------------------------------------------------------
| FORMATA TAMANHO
|--------------------------------------------------------------------------
*/

function formatarTamanho(bytes)
{
    if (!bytes) {
        return '0 B';
    }

    const unidades = ['B', 'KB', 'MB', 'GB', 'TB'];

    let tamanho = bytes;
    let i = 0;

    while (
        tamanho >= 1024 &&
        i < unidades.length - 1
    ) {
        tamanho /= 1024;
        i++;
    }

    return `${tamanho.toFixed(2)} ${unidades[i]}`;
}


/*
|--------------------------------------------------------------------------
| ENVIO DO FORMULÁRIO
|--------------------------------------------------------------------------
*/

document
    .getElementById('restore-form')
    .addEventListener('submit', function (event) {

        /*
        |--------------------------------------------------------------------------
        | Adiciona FULL selecionado
        |--------------------------------------------------------------------------
        */

        if (fullSelecionado) {

            adicionarCampoHidden(
                'full_nome',
                fullSelecionado.nome ?? ''
            );

            adicionarCampoHidden(
                'full_caminho',
                fullSelecionado.caminho ?? ''
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Adiciona DIFF selecionado
        |--------------------------------------------------------------------------
        */

        if (diffSelecionado) {

            adicionarCampoHidden(
                'diff_nome',
                diffSelecionado.nome ?? ''
            );

            adicionarCampoHidden(
                'diff_caminho',
                diffSelecionado.caminho ?? ''
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Adiciona LOGs selecionados
        |--------------------------------------------------------------------------
        */

        logsSelecionados.forEach((log, index) => {

            adicionarCampoHidden(
                `logs_selecionados[${index}][nome]`,
                log.nome ?? ''
            );

            adicionarCampoHidden(
                `logs_selecionados[${index}][caminho]`,
                log.caminho ?? ''
            );

        });

    });


/*
|--------------------------------------------------------------------------
| CAMPO HIDDEN
|--------------------------------------------------------------------------
*/

function adicionarCampoHidden(nome, valor)
{
    const input =
        document.createElement('input');

    input.type = 'hidden';

    input.name = nome;

    input.value = valor;

    document
        .getElementById('restore-form')
        .appendChild(input);
}

</script>