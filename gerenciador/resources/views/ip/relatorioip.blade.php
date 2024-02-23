<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
            }

            th, td {
                padding: 10px;
                text-align: left;
            }
        </style>
    </head>
    <body>
    <h2>Relatório de incidente IP</h2>

        <table>
            <thead>
            <tr>
                <th>Ip</th>
                <th>Cidade</th>
                <th>Região</th>
                <th>Continente</th>
                <th>Empresa</th>
                <th>Quantidade</th>
            </tr>
            </thead>
                <tbody>
                    @foreach($relatorioIp as $ipData)
                        <tr>
                            <td>{{ $ipData->ip }}</td>
                            <td>{{ $ipData->cidade }}</td>
                            <td>{{ $ipData->regiao }}</td>
                            <td>{{ $ipData->continente }}</td>
                            <td>{{ $ipData->empresa }}</td>
                            <td>{{ $ipData->quantidade }}</td>
                        </tr>
                    @endforeach
                </tbody>
        </table>
    </body>
</html>

