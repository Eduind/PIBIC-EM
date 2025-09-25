<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tb_alimento extends Model
{
    use SoftDeletes;

    protected $table = 'tb_alimentos';
    protected $primaryKey = 'idAlimento';

    protected $casts = [
        'alergicos' => 'array',
        'contem_gluten' => 'boolean',
    ];

    protected $fillable = [
        'nomeAlimento',
        'ingredientes',
        'calorias',
        'carboidratos',
        'proteinas',
        'gorduras_totais',
        'contem_gluten',
        'alergicos',
    ];

    public function cardapios()
    {
        return $this->hasMany(tb_cardapio_refeicoes::class, 'alimento_id', 'idAlimento');
    }
}
