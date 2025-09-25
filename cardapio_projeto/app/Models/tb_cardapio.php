<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tb_cardapio extends Model
{
    use SoftDeletes;

    protected $table = 'tb_cardapios';
    protected $primaryKey = 'idCardapio';

    protected $fillable = [
        'nomeCardapio',
        'data_inicio',
        'data_fim',
    ];

    public function refeicoes()
    {
        return $this->hasMany(tb_cardapio_refeicoes::class, 'cardapio_id', 'idCardapio');
    }
}


