<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class LibroSeeder extends AbstractSeed
{

    public function getDependencies(): array
    {
        return ['AutorSeeder'];
    }

    public function run(): void
    {
        $data = [
            [
                'imagen' => 'el-aleph.png',
                'titulo' => 'El Aleph',
                'descripcion' => 'Una serie de cuentos que exploran temas filosóficos y fantásticos.',
                'precio' => 2500.00,
                'genero_id' => 1,
                'editorial_id' => 1,
                'idioma_id' => 1,
                'stock' => 15,
                'autor_id' => 1 // Jorge Luis Borges
            ],
            [
                'imagen' => 'ficciones.png',
                'titulo' => 'Ficciones',
                'descripcion' => 'Recopilación de relatos que juegan con laberintos, espejos y libros infinitos.',
                'precio' => 2800.00,
                'genero_id' => 1,
                'editorial_id' => 2,
                'idioma_id' => 1,
                'stock' => 8,
                'autor_id' => 1 // Jorge Luis Borges
            ],
            [
                'imagen' => 'rayuela.jpg',
                'titulo' => 'Rayuela',
                'descripcion' => 'Una novela experimental donde el lector puede elegir el orden de los capítulos.',
                'precio' => 3500.00,
                'genero_id' => 2,
                'editorial_id' => 3,
                'idioma_id' => 1,
                'stock' => 5,
                'autor_id' => 2 // Julio Cortázar
            ],
            [
                'imagen' => 'bestiario.jpg',
                'titulo' => 'Bestiario',
                'descripcion' => 'Libro de cuentos donde lo cotidiano se mezcla con lo fantástico e inquietante.',
                'precio' => 2200.50,
                'genero_id' => 3,
                'editorial_id' => 4,
                'idioma_id' => 1,
                'stock' => 12,
                'autor_id' => 2 // Julio Cortázar
            ],
            [
                'imagen' => 'cien-años-de-soledad.jpg',
                'titulo' => 'Cien años de soledad',
                'descripcion' => 'La mítica historia de la familia Buendía en el pueblo de Macondo.',
                'precio' => 4000.00,
                'genero_id' => 4,
                'editorial_id' => 4,
                'idioma_id' => 1,
                'stock' => 20,
                'autor_id' => 3 // Gabriel García Márquez
            ],
            [
                'imagen' => 'cronica-de-una-muerte-anunciada.jpg',
                'titulo' => 'Crónica de una muerte anunciada',
                'descripcion' => 'Una reconstrucción casi periodística de un asesinato que todos sabían que ocurriría.',
                'precio' => 1900.00,
                'genero_id' => 2,
                'editorial_id' => 5,
                'idioma_id' => 1,
                'stock' => 10,
                'autor_id' => 3 // Gabriel García Márquez
            ],
            [
                'imagen' => 'el-amor-en-los-tiempos-del-colera.jpg',
                'titulo' => 'El amor en los tiempos del cólera',
                'descripcion' => 'Una historia de amor incondicional que perdura durante más de medio siglo.',
                'precio' => 3100.00,
                'genero_id' => 5,
                'editorial_id' => 6,
                'idioma_id' => 1,
                'stock' => 7,
                'autor_id' => 3 // Gabriel García Márquez
            ]
        ];

        $this->table('libro')->insert($data)->saveData();
    }
}
