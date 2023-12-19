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
<h2>Relatório de Vendas de Produtos</h2>

<table>
    <thead>
    <tr>
        <th>Nome do Produto</th>
        <th>Quantidade Vendida</th>
        <th>Valor Unitário</th>
        <th>Valor Total</th>
    </tr>
    </thead>
    <tbody>
    @php
        $somaTotal = 0; // Inicialize a variável de soma total
    @endphp

    @foreach($compras as $compra)
        <tr>
            <td>{{$compra->nome_do_produto}}</td>
            <td>{{$compra->quantidade_vendida}}</td>
            <td>{{$compra->valor_unitario}}</td>
            @php
                $valorTotal = $compra->quantidade_vendida * $compra->valor_unitario;
                $somaTotal += $valorTotal; // Adicione o valor total à soma total
            @endphp
            <td>{{$valorTotal}}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="3">Soma Total:</td>
        <td>{{$somaTotal}}</td>
    </tr>
    </tbody>
</table>
</body>
</html>

