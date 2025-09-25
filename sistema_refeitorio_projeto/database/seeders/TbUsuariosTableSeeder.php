<?php

namespace Database\Seeders;

use App\Models\tb_usuario;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TbUsuariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        tb_usuario::create([
            'nome_usuario' => 'Nutricionista',
            'email' => 'nutricionista@gmail.com',
            'senha' => bcrypt('12345678'),
            'active' => true,
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
