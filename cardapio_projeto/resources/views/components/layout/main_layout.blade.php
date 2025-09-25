<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{asset('assets/bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ $css ?? '' }}">
</head>

<body>
    <header>
        <nav>
            <a href="{{route('home')}}" class="logo">
                <img src="{{asset('assets/image/logo.png')}}" alt="Logo">
                <span class="logo-text">Cantina Escolar</span>
            </a>
            <ul class="nav-list">
                <li><a href="{{route('ExibirCardapios')}}">Cardápio</a></li>
                <li><a href="{{route('CriarCardapios')}}">Criar Cardápio</a></li>
                <li><a href="{{route('home')}}">Alimentos</a></li>
                <li><a href="{{route('logout')}}"><i class="bi bi-box-arrow-right"></i></a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="content">
            {{ $slot }}
        </div>
    </main>

</body>

</html>
