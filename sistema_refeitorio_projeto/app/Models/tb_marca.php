<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tb_marca extends Model
{
    use SoftDeletes;
    protected $table = 'tb_marca';

    protected $primaryKey = 'idMarca';

    public function produtos():BelongsToMany{
        return $this->belongsToMany(tb_produto::class,'tb_marca_produto', 'marca_id', 'produto_id','idMarca', 'idProdutos');
    }

}
