<x-layout title="Proxmox">

    <a href="{{ route('home.index') }}" class="btn btn-dark my-3">
        Home
    </a>

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">
                Proxmox API
            </h5>
        </div>

        <div class="card-body">

            {{-- Método --}}
            <div class="mb-3">

                <label class="form-label">
                    Método
                </label>

                <div class="btn-group w-100" role="group">

                    <input
                        type="radio"
                        class="btn-check"
                        name="metodo"
                        id="metodo_get"
                        value="GET"
                        checked
                    >

                    <label
                        class="btn btn-outline-primary"
                        for="metodo_get"
                    >
                        GET
                    </label>


                    <input
                        type="radio"
                        class="btn-check"
                        name="metodo"
                        id="metodo_post"
                        value="POST"
                    >

                    <label
                        class="btn btn-outline-success"
                        for="metodo_post"
                    >
                        POST
                    </label>


                    <input
                        type="radio"
                        class="btn-check"
                        name="metodo"
                        id="metodo_put"
                        value="PUT"
                    >

                    <label
                        class="btn btn-outline-warning"
                        for="metodo_put"
                    >
                        PUT
                    </label>


                    <input
                        type="radio"
                        class="btn-check"
                        name="metodo"
                        id="metodo_patch"
                        value="PATCH"
                    >

                    <label
                        class="btn btn-outline-info"
                        for="metodo_patch"
                    >
                        PATCH
                    </label>


                    <input
                        type="radio"
                        class="btn-check"
                        name="metodo"
                        id="metodo_delete"
                        value="DELETE"
                    >

                    <label
                        class="btn btn-outline-danger"
                        for="metodo_delete"
                    >
                        DELETE
                    </label>

                </div>

            </div>


            {{-- Rota --}}
            <div class="mb-3">

                <label
                    for="rota"
                    class="form-label"
                >
                    Rota
                </label>

                <input
                    type="text"
                    id="rota"
                    class="form-control"
                    placeholder="/nodes/pve01/qemu"
                    autocomplete="off"
                >

                <div class="form-text">
                    Informe somente o restante da rota da API.
                    Exemplo: /nodes/pve01/qemu
                </div>

            </div>


            {{-- Parâmetros --}}
            <div class="mb-3">

                <label
                    for="parametros"
                    class="form-label"
                >
                    Parâmetros
                </label>

                <textarea
                    id="parametros"
                    class="form-control font-monospace"
                    rows="8"
                    spellcheck="false"
                    placeholder='{
    "memory": 8192
}'>{}</textarea>

                <div class="form-text">
                    Informe os parâmetros em JSON.
                    Para uma requisição sem parâmetros, use <code>{}</code>.
                </div>

            </div>


            {{-- Executar --}}
            <div class="mb-4">

                <button
                    type="button"
                    id="btnExecutar"
                    class="btn btn-primary"
                >
                    <span id="textoBotao">
                        Executar
                    </span>

                    <span
                        id="spinnerBotao"
                        class="spinner-border spinner-border-sm d-none ms-2"
                        role="status"
                        aria-hidden="true"
                    ></span>

                </button>

            </div>


            {{-- Mensagem --}}
            <div
                id="mensagem"
                class="alert d-none"
                role="alert"
            ></div>


            {{-- Retorno --}}
            <div>

                <div class="d-flex justify-content-between align-items-center mb-2">

                    <label class="form-label mb-0">
                        Retorno
                    </label>

                    <span
                        id="statusHttp"
                        class="badge bg-secondary"
                    >
                        -
                    </span>

                </div>

                <pre
                    id="retorno"
                    class="bg-dark text-light p-3 rounded"
                    style="
                        min-height: 350px;
                        max-height: 700px;
                        overflow: auto;
                        white-space: pre-wrap;
                        word-break: break-word;
                    "
                >Aguardando execução...</pre>

            </div>

        </div>

    </div>


    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const btnExecutar = document.getElementById('btnExecutar');

            const textoBotao = document.getElementById('textoBotao');

            const spinnerBotao = document.getElementById('spinnerBotao');

            const rotaInput = document.getElementById('rota');

            const parametrosInput = document.getElementById('parametros');

            const retorno = document.getElementById('retorno');

            const mensagem = document.getElementById('mensagem');

            const statusHttp = document.getElementById('statusHttp');


            /*
             * Exibe mensagem na tela
             */
            function mostrarMensagem(texto, tipo = 'danger') {

                mensagem.className = 'alert alert-' + tipo;

                mensagem.textContent = texto;

            }


            /*
             * Esconde mensagem
             */
            function esconderMensagem() {

                mensagem.className = 'alert d-none';

                mensagem.textContent = '';

            }


            /*
             * Formata JSON para exibição
             */
            function formatarRetorno(dados) {

                if (typeof dados === 'string') {

                    try {

                        return JSON.stringify(
                            JSON.parse(dados),
                            null,
                            4
                        );

                    } catch (e) {

                        return dados;

                    }

                }

                return JSON.stringify(
                    dados,
                    null,
                    4
                );

            }


            /*
             * Executar requisição
             */
            btnExecutar.addEventListener('click', async function () {

                esconderMensagem();


                /*
                 * Método selecionado
                 */
                const metodoElement = document.querySelector(
                    'input[name="metodo"]:checked'
                );


                if (!metodoElement) {

                    mostrarMensagem(
                        'Selecione um método HTTP.'
                    );

                    return;

                }


                const metodo = metodoElement.value;


                /*
                 * Rota
                 */
                let rota = rotaInput.value.trim();


                if (!rota) {

                    mostrarMensagem(
                        'Informe a rota do Proxmox.'
                    );

                    rotaInput.focus();

                    return;

                }


                /*
                 * Garante que a rota comece com /
                 */
                if (!rota.startsWith('/')) {

                    rota = '/' + rota;

                }


                /*
                 * Parâmetros
                 */
                let parametros = {};

                const parametrosTexto =
                    parametrosInput.value.trim();


                if (parametrosTexto) {

                    try {

                        parametros = JSON.parse(
                            parametrosTexto
                        );

                    } catch (erro) {

                        mostrarMensagem(
                            'Os parâmetros não possuem um JSON válido.'
                        );

                        parametrosInput.focus();

                        return;

                    }

                }


                /*
                 * Limpa tela anterior
                 */
                retorno.textContent =
                    'Executando requisição...';

                statusHttp.textContent = '-';

                statusHttp.className =
                    'badge bg-secondary';


                /*
                 * Loading
                 */
                btnExecutar.disabled = true;

                textoBotao.textContent =
                    'Executando...';

                spinnerBotao.classList.remove(
                    'd-none'
                );


                try {

                    /*
                     * Chamada AJAX para o Laravel
                     */
                    const response = await fetch(
                        "{{ route('proxmox.executar') }}",
                        {
                            method: 'POST',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    "{{ csrf_token() }}"

                            },

                            body: JSON.stringify({

                                metodo: metodo,

                                rota: rota,

                                parametros: parametros

                            })

                        }
                    );


                    /*
                     * Status HTTP
                     */
                    statusHttp.textContent =
                        response.status +
                        ' ' +
                        response.statusText;


                    if (response.ok) {

                        statusHttp.className =
                            'badge bg-success';

                    } else {

                        statusHttp.className =
                            'badge bg-danger';

                    }


                    /*
                     * Tenta interpretar como JSON
                     */
                    const textoResposta =
                        await response.text();


                    let dadosResposta;


                    try {

                        dadosResposta =
                            JSON.parse(textoResposta);

                    } catch (erro) {

                        dadosResposta =
                            textoResposta;

                    }


                    /*
                     * Mostra retorno
                     */
                    retorno.textContent =
                        formatarRetorno(
                            dadosResposta
                        );


                    /*
                     * Erro HTTP
                     */
                    if (!response.ok) {

                        let mensagemErro =
                            'A requisição retornou HTTP ' +
                            response.status +
                            '.';


                        if (
                            dadosResposta &&
                            typeof dadosResposta === 'object' &&
                            dadosResposta.message
                        ) {

                            mensagemErro =
                                dadosResposta.message;

                        }


                        mostrarMensagem(
                            mensagemErro,
                            'danger'
                        );

                    } else {

                        mostrarMensagem(
                            'Requisição executada com sucesso.',
                            'success'
                        );

                    }

                } catch (erro) {

                    /*
                     * Erro de comunicação com Laravel
                     */
                    statusHttp.textContent =
                        'ERRO';

                    statusHttp.className =
                        'badge bg-danger';


                    retorno.textContent =
                        erro.message;


                    mostrarMensagem(
                        'Não foi possível comunicar com o servidor Laravel: ' +
                        erro.message,
                        'danger'
                    );

                } finally {

                    /*
                     * Finaliza loading
                     */
                    btnExecutar.disabled = false;

                    textoBotao.textContent =
                        'Executar';

                    spinnerBotao.classList.add(
                        'd-none'
                    );

                }

            });


            /*
             * Ctrl + Enter executa a requisição
             */
            parametrosInput.addEventListener(
                'keydown',
                function (event) {

                    if (
                        event.ctrlKey &&
                        event.key === 'Enter'
                    ) {

                        btnExecutar.click();

                    }

                }
            );


            /*
             * Enter no campo rota também executa
             */
            rotaInput.addEventListener(
                'keydown',
                function (event) {

                    if (event.key === 'Enter') {

                        event.preventDefault();

                        btnExecutar.click();

                    }

                }
            );

        });

    </script>

</x-layout>