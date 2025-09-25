<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class tb_entrada extends Model
{
    protected $table = 'tb_entrada';

    protected $primaryKey = 'idEntradas';

    public function produto(): BelongsTo{
        return $this->belongsTo(tb_produto::class);
    }

    public function fornecedor(): BelongsTo{
        return $this->belongsTo(tb_fornecedor::class);
    }
}
