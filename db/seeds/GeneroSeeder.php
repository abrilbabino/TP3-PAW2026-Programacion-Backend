<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class GeneroSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data=
     [
        [
            'id' => 1,
            'nombre' => 'Ficción',
        ],
        [
            'id' => 2,
            'nombre' => 'Novela',
        ],
        [
            'id' => 3,
            'nombre' => 'Fantástico',
        ],
        [
            'id' => 4,
            'nombre' => 'Realismo Mágico',
        ],
        [
            'id' => 5,
            'nombre' => 'Romance',
        ]
     ];   

     $this->table('genero')->insert($data)->saveData();
    }
}