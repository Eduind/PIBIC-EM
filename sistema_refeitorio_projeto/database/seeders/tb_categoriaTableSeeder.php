<?php

namespace Database\Seeders;

use App\Models\tb_categoria;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class tb_categoriaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        tb_categoria::created([
            'nomeCategoria' => 'Grãos',
            'created_at' => Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
    }
}
