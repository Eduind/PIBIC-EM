<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class tb_produto extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'idProdutos';

    public function marcas():BelongsToMany{
        return $this->belongsToMany(tb_marca::class,'tb_marca_produto', 'produto_id', 'marca_id','idProdutos', 'idMarca');
    }

    public function categorias():BelongsToMany{
        return $this->belongsToMany(tb_categoria::class,'tb_categoria_produto', 'produto_id', 'categoria_id','idProdutos', 'idCategorias');
    }
}
