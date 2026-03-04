<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f0f0f0; }
        h2 { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

    <h2>Relatório Seções Cloud</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Usuário</th>
                <th>Senha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dados as $dado)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dado->usuario }}</td>
                    <td>{{ $dado->senha }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>