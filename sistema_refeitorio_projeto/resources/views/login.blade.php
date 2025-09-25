<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/images/logo_ifba.png')}}" type="image/png">
    <link rel="stylesheet" href="{{asset('assets/bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <title>Login - Nosso Refeitório</title>
</head>
<body>
    <div class="container-login">
        <div class="esquerda">
            <img src="{{asset('assets/images/logo.png')}}" alt="Logo Nosso Refeitório" class="logo">

            @if(session('invalid_login'))
                <div class="alert alert-danger text-center px-4 py-3">
                    {{ session('invalid_login') }}
                </div>
            @endif
            <h1>Faça login</h1>
            <form action="{{route('loginSubmit')}}" method="post" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="mb-2">
                    <input type="email" id="email" name="text_email" placeholder="E-mail" value="{{old('text_email')}}" required>
                    @error('text_email')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-2">
                    <input type="password" id="senha" name="text_password" placeholder="Senha" value="{{old('text_password')}}" required>
                    @error('text_password')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" name="btn">Entrar</button>
            </form>
        </div>

        <div class="direita">
            <img src="{{asset('assets/images/img.png')}}" alt="Imagem Ilustrativa Nosso Refeitório">
        </div>
    </div>
    <script src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
