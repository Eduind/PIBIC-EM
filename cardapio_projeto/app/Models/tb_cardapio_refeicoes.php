<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tb_cardapio_refeicoes extends Model
{
    protected $table = 'tb_cardapio_refeicoes';

    protected $fillable = [
        'cardapio_id',
        'alimento_id',
        'dia_semana',
        'horario',
    ];

    public function cardapio()
    {
        return $this->belongsTo(tb_cardapio::class, 'cardapio_id', 'idCardapio');
    }

    public function alimento()
    {
        return $this->belongsTo(tb_alimento::class, 'alimento_id', 'idAlimento');
    }
}
