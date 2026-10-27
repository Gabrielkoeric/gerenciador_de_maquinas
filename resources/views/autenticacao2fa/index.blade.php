
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autenticação em dois fatores</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-12 col-sm-10 col-md-6 col-lg-4">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h4 class="text-center mb-3">
                        Verificação de segurança
                    </h4>

                    <p class="text-muted text-center">
                        Enviamos um código de 8 dígitos para seu e-mail.
                        Digite-o abaixo para continuar.
                    </p>

                    @if (session('mensagemErro'))
                        <div class="alert alert-danger">
                            {{ session('mensagemErro') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first('codigo') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('2fa.validar') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="codigo" class="form-label">
                                Código de autenticação
                            </label>

                            <input
                                type="text"
                                id="codigo"
                                name="codigo"
                                class="form-control form-control-lg text-center @error('codigo') is-invalid @enderror"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                pattern="[0-9]{8}"
                                maxlength="8"
                                minlength="8"
                                placeholder="00000000"
                                value="{{ old('codigo') }}"
                                required
                                autofocus
                            >

                            @error('codigo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Validar código
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            O código expira em 5 minutos.
                        </small>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
