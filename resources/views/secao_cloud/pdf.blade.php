<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.5;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        h2 {
            margin-top: 25px;
            border-bottom: 1px solid #999;
            padding-bottom: 5px;
        }

        .box {
            border: 1px solid #999;
            padding: 10px;
            margin-bottom: 15px;
            background: #f8f8f8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #efefef;
        }

        .uuid {
            font-size: 14px;
            font-weight: bold;
            color: #0b5394;
        }

        .link {
            color: #0b5394;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Guia de Acesso Sistemas Escalasoft</h1>

<div class="box">
    <strong>Cliente:</strong> {{ $dados->first()->nome_cliente }}<br>
    <strong>UUID:</strong>

    <div class="uuid">
        {{ $dados->first()->uuid }}
    </div>
</div>

<h2>1. Download do Executável</h2>

<p>
Faça o download do executável através do endereço abaixo:
</p>

<p class="link">
https://cloudrunner.escalasoft.com.br/runner/EscalaCloudLauncher.exe
</p>

<h2>2. Como acessar</h2>

<ol>
    <li>Baixe o executável.</li>
    <li>Execute o programa.</li>
    <li>Informe o UUID apresentado neste documento.</li>
    <li>Clique em <strong>Conectar</strong>.</li>
    <li>O sistema listará todos os ambientes disponíveis.</li>
    <li>Clique em <strong>Instalar</strong> no ambiente desejado.</li>
</ol>

<h2>3. Seções disponíveis</h2>

<table>
    <thead>
    <tr>
        <th width="40">#</th>
        <th>Usuário</th>
        <th>Senha</th>
    </tr>
    </thead>
    <tbody>

    @foreach($dados as $dado)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $dado->usuario }}</td>
            <td>{{ $dado->senha }}</td>
        </tr>
    @endforeach

    </tbody>
</table>

<br>

<h2>4. Acesso Escala Web</h2>

<p>
Para acesso ao <strong>Escala Web</strong>, utilize o endereço abaixo para realizar o login:
</p>

<p>
<strong>URL:</strong><br>
https://{{ $dados->first()->apelido }}.escalasoft.com.br
</p>

<h2>5. Escala Web Service (WS)</h2>

<p>
Para realizar integrações utilizando a API ou o Web Service da Escalasoft, utilize o endereço abaixo:
</p>

<p>
<strong>Endereço:</strong><br>
{{ $dados->first()->apelido }}.cloud.escalasoft.com.br:{{ $dados->first()->porta }}{{ $dados->first()->config_ws ?: '/escalasoft' }}
</p>

<h2>6. Observações</h2>

<ul>
    <li>Utilize sempre o UUID informado neste documento para acessar os sistemas disponibilizados.</li>

    <li>Para acessar o ambiente de consultoria, realize o mesmo procedimento de instalação descrito anteriormente. Quando forem solicitadas as credenciais de acesso via RDP, utilize as mesmas credenciais da Seção Cloud de produção. Posteriormente, quando o sistema solicitar as credenciais de autenticação da Escalasoft, utilize as seguintes informações:
        <br><strong>Usuário:</strong> cliente.escalasoft
        <br><strong>Senha:</strong> cliente.escalasoft
    </li>

    <li>Em caso de dúvidas ou dificuldades durante o processo de instalação ou acesso, entre em contato com a equipe de suporte da Escalasoft.</li>
</ul>

</body>
</html>