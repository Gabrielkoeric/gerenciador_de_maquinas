// server.js
const express = require('express');
const http = require('http');
const socketIO = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = socketIO(server);

io.on('connection', (socket) => {
    console.log('Cliente conectado');

    // Evento para receber comandos do cliente
    socket.on('command', (command) => {
        console.log(`Comando recebido: ${command}`);

        // Simulação de execução de comando (substitua com a lógica real)
        const output = `Saída do comando '${command}': Simulação de execução`;

        // Enviar a saída de volta para o cliente
        io.emit('output', output);
    });

    // Evento de desconexão do cliente
    socket.on('disconnect', () => {
        console.log('Cliente desconectado');
    });
});

const port = 3000;
server.listen(port, () => {
    console.log(`Servidor WebSocket ouvindo na porta ${port}`);
});

