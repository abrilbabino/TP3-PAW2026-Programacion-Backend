<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class EditorialSeeder extends AbstractSeed
{
    public function run(): void
    {
     $data=
     [
        [
            'id' => 1,
            'nombre' => 'DeBolsillo',
        ],
        [
            'id' => 2,
            'nombre' => 'Sur',
        ],
        [
            'id' => 3,
            'nombre' => 'Alfaguara',
        ],
        [
            'id' => 4,
            'nombre' => 'Sudamericana',
        ],
        [
            'id' => 5,
            'nombre' => 'Diana',
        ],
        [
            'id' => 6,
            'nombre' => 'Oveja Negra',
        ]
     ];   

     $this->table('editorial')->insert($data)->saveData();
    }
}