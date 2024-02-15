<!doctype html>
<html lang="pt-BR">
<head>
<link rel="icon" href="storage/home/cloud.png" type="image/png">
    <link rel="shortcut icon" href="storage/home/cloud.png" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }} - Gerenciamento de Ambientes</title>
    <link rel="stylesheet" href="{{ secure_asset('css/app.css') }}">
</head>
<body class="bg-light">
<nav href="{{route('home.index')}}" class="nav navbar-expand-lg navbar-light bg-secondary">
    <div class="container-fluid d-flex align-items-center justify-content-between" style="height: 100px; margin-left: 100px; margin-right: 100px; margin-top: 2px;">
        <h1 style="color: #fff">{{$title}}</h1>

        @auth
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{Auth::user()->imagem}}" style="max-width: 65px; border-radius: 50%;">
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{route('usuario.edit', Auth::User('id'))}}">Editar Usuario</a>
                    <a class="dropdown-item" href="{{route('logout')}}">Sair</a>
                </div>
            </div>
        @endauth
    </div>
</nav>



<div class="container">
    @if($errors->any())
        <div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{ $slot }}

</div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
