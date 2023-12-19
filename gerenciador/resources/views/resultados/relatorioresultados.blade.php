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
<h2>Relatório dos Resultados do Evento</h2>

<table>
    <thead>
    <tr>
        <th>Descrição</th>
        <th>Valor</th>
    </tr>
    </thead>
    <tbody>

    <tr>
        <td>Vendas De Produtos</td>
        <td>R$: {{$totalCompraEstoque}}</td>
    </tr>
    <tr>
        <td>Vendas De Ingressos</td>
        <td>R$: {{$totalCompraIngresso}}</td>
    </tr>
    <tr>
        <td>Custo de Produtos</td>
        <td>R$: {{$totalValorCusto}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>SubTotal</td>
        <td>R$: {{$totalCompraEstoque + $totalCompraIngresso - $totalValorCusto }}</td>
    </tr>
    </tbody>
</table>
</body>
</html>

