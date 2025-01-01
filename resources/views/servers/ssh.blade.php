<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSH Interface</title>
    <!-- Adicione isso à seção <head> da sua página Laravel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.0.4/socket.io.js"></script>
</head>
<body>
<h1>SSH Interface</h1>

<form method="post" action="{{ route('server.ssh', $server->id_server) }}">
    @csrf
    <label for="command">Digite um comando:</label>
    <input type="text" id="command" name="command" required>
    <button type="submit">Executar</button>
</form>

<script>
    const socket = io('http://localhost:3000');
    const outputElement = document.getElementById('output');

    socket.on('output', (output) => {
        outputElement.innerHTML += `<div>${output}</div>`;
    });

    function sendCommand() {
        const commandInput = document.getElementById('command');
        const command = commandInput.value;
        socket.emit('command', command);
        commandInput.value = '';
    }
</script>   

@isset($output)
    <h2>Resultado:</h2>
    <pre>{{ $output }}</pre>
@endisset
</body>
</html>
