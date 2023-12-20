<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSH Interface</title>
</head>
<body>
<h1>SSH Interface</h1>

<form method="post" action="{{ route('server.ssh', $server->id_server) }}">
    @csrf
    <label for="command">Digite um comando:</label>
    <input type="text" id="command" name="command" required>
    <button type="submit">Executar</button>
</form>

@isset($output)
    <h2>Resultado:</h2>
    <pre>{{ $output }}</pre>
@endisset
</body>
</html>
