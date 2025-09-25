<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('assets/fontawesome/css/all.min.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/logo_ifba.png')}}" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{asset('css/registro_itens.css')}}">
    <title>{{$title}}</title>
</head>
<body>
    <div class="sidebar">
        <div class="icone">
            <i class="bi bi-list" id="btn"></i>
        </div>
        <ul class="nav-list">
            <li>
                <a href="{{route('home')}}">
                    <i class="bi bi-box-seam"></i>
                    <span class="link-name">Registrar itens</span>
                </a>
            </li>
            <li>
                <a href="{{route('estoque')}}">
                    <i class="bi bi-archive"></i>
                    <span class="link-name">Estoque</span>
                </a>
            </li>
            <li>
                <a href="{{route('movimentacao')}}">
                    <i class="bi bi-truck"></i>
                    <span class="link-name">Pedidos</span>
                </a>
            </li>
            <li>
                <a href="{{route('relatorios')}}">
                    <i class="bi bi-graph-up"></i>
                    <span class="link-name">Relatórios</span>
                </a>
            </li>
        </ul>
        <div class="log-out">
            <a href="{{route('logout')}}">
                <i class="bi bi-box-arrow-right"></i>
                <span class="link-name">Log-out</span>
            </a>
        </div>
    </div>

    <div class="main">
        <div class="barra-superior">
            <h3>{{$title}}</h3>
        </div>
        {{$slot}}
    </div>

    </div>
    <script src="{{asset('js/script.js')}}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
</body>
</html>
