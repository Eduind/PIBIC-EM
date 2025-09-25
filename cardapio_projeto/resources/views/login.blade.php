<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('assets/bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">

</head>

<body>
    <div class="login-container">
        @if (session('invalid_login'))
            <div class="alert alert-danger text-center px-4 py-3">
                {{ session('invalid_login') }}
            </div>
        @endif
        <h2>Faça login</h2>
        <form action="{{ route('loginSubmit') }}" method="POST" class="login-form">
            @csrf
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="text_email" placeholder="Digite seu e-mail" required>
                @error('text_email')
                    <small class="text-danger d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="text_senha" placeholder="Digite sua senha" required>
                @error('text_senha')
                    <small class="text-danger d-block">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>
    </div>
</body>

</html>
