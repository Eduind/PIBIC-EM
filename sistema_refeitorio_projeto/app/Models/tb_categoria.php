<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class tb_categoria extends Model
{
    use SoftDeletes;
    protected $table = 'tb_categorias';
    protected $primaryKey = 'idCategorias';

    public function produtos():BelongsToMany{
        return $this->belongsToMany(tb_produto::class,'tb_categoria_produto', 'categoria_id', 'produto_id','idCategorias', 'idProdutos');
    }


}
