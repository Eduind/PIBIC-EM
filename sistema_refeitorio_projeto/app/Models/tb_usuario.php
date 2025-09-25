<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthUser;

class tb_usuario extends AuthUser
{
    protected $table = 'tb_usuarios';
}
