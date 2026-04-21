<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class AutorSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'nombre' => 'Jorge Luis Borges',
                'biografia' => 'Escritor, ensayista y poeta argentino, una de las figuras más importantes de la literatura en español del siglo XX.'
            ],
            [
                'id' => 2,
                'nombre' => 'Julio Cortázar',
                'biografia' => 'Escritor e intelectual argentino nacionalizado francés, autor de la revolucionaria novela Rayuela.'
            ],
            [
                'id' => 3,
                'nombre' => 'Gabriel García Márquez',
                'biografia' => 'Escritor colombiano y Premio Nobel de Literatura, máximo exponente del realismo mágico.'
            ],
            [
                'id' => 4,
                'nombre' => 'Isabel Allende',
                'biografia' => 'Escritora chilena-estadounidense de gran éxito comercial, famosa por La casa de los espíritus.'
            ],
            [
                'id' => 5,
                'nombre' => 'Ernesto Sabato',
                'biografia' => 'Escritor, físico y pintor argentino, autor de ensayos existencialistas y novelas como El Túnel.'
            ]
        ];

        $this->table('autor')->insert($data)->saveData();
    }
}
