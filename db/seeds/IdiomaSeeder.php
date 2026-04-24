<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class IdiomaSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data=
     [
        [
            'id' => 1,
            'nombre' => 'Español',
        ],
        [
            'id' => 2,
            'nombre' => 'Inglés',
        ],
        [
            'id' => 3,
            'nombre' => 'Francés',
        ],
        [
            'id' => 4,
            'nombre' => 'Italiano',
        ]
     ];   

     $this->table('idioma')->insert($data)->saveData();
    }
}