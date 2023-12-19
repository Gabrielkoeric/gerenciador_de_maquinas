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
<h2>Relatório de Vendas de Ingressos</h2>

<table>
    <thead>
    <tr>
        <th>Ingresso</th>
        <th>Nº Lote</th>
        <th>Valor Ingresso</th>
        <th>Valor Lote</th>
        <th>Qtd.</th>
        <th>Valor Total</th>
    </tr>
    </thead>
    <tbody>
    @php
        $somaTotal = 0; // Inicialize a variável de soma total
    @endphp

    @foreach($compras as $compra)
        <tr>
            <td>{{$compra->nome_do_ingresso}}</td>
            <td>{{$compra->numero_do_lote}}</td>
            <td>{{$compra->valor_do_ingresso}}</td>
            <td>{{$compra->adicionalLote}}</td>
            <td>{{$compra->quantidade_vendida}}</td>

            @php
                $valorTotal = ($compra->valor_do_ingresso + $compra->adicionalLote) * $compra->quantidade_vendida;
                $somaTotal += $valorTotal;
            @endphp
            <td>{{$valorTotal}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="5">Soma Total:</td>
        <td>{{$somaTotal}}</td>
    </tr>
    </tbody>
</table>
</body>
</html>

