<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('assets/fontawesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/bootstrap/bootstrap.min.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/logo_ifba.png')}}" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{asset('css/exibir.css')}}">
    <title>{{$title}}</title>
</head>
<body>
    <div>
        @if (session('criacao_valida'))
            <div class="alert alert-success text-center px-4 py-3">
                {{ session('criacao_valida') }}
            </div>
        @endif
        @if (session('erro'))
            <div class="alert alert-danger text-center px-4 py-3">
                {{ session('erro') }}
            </div>
        @endif
        <h2>{{$text}}</h2>
        <table class="table-style">
            <thead>
                <tr>
                    <th>Nome do Produto</th>
                    <th>Tamanho</th>
                    <th>Unidade de Medida</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                {{$slot}}
            </tbody>
        </table>
        <div class="button-group">
            <a href="{{route('home')}}" class="btn-link">
                <i class="fa-solid fa-ban"></i> Voltar
            </a>
        </div>
    </div>
</body>
</html>
