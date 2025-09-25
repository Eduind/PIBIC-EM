<?php

namespace App\Http\Controllers;

use App\Models\tb_produto;
use App\Models\tb_usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function Login(){
        return view('login');
    }

    public function LoginSubmit(Request $request){

        $credentials = $request->validate(
            //validação
            [
                'text_email' => 'required|email',
                'text_password' => 'required|min:6|max:16'
            ],
            //mensagens de erro
            [
                'text_email.required' => 'O email é obrigatório',
                'text_email.email' => 'O email deve ser um email válido',
                'text_password.required' => 'A senha é obrigatória',
                'text_password.min' => 'A senha deve ter pelo menos :min caracteres',
                'text_password.max' => 'A senha deve ter no máximo :max caracteres'
            ]);

        $usuario = tb_usuario::where('email',$credentials['text_email'])
                                ->where('active',true)
                                ->whereNotNull('email_verified_at')
                                ->whereNull('deleted_at')
                                ->first();

        if(!$usuario){
            return back()->withInput()->with([
                'invalid_login' => 'Login invalido'
            ]);
        }

        if (!Hash::check($credentials['text_password'], $usuario->senha)) {
            return back()->withInput()->with(['invalid_login' => 'Usuário ou senha inválidos']);
        }

        $usuario->last_login_at = now();
        $usuario->save();

        $request->session()->regenerate();
        Auth::login($usuario);

        return redirect()->intended(route('home'));
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
