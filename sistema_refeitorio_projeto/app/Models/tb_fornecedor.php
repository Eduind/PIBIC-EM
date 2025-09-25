<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tb_fornecedor extends Model
{
    use SoftDeletes;
    protected $table = 'tb_fornecedor';

    protected $primaryKey = 'idFornecedor';
}
