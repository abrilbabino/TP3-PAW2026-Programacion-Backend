<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class LibroMigration extends AbstractMigration
{
    public function change(): void
    {
        $tableGenero = $this->table('genero');
        $tableGenero->addColumn('nombre', 'string', ['limit' => 60])
                    ->create();

        $tableEditorial = $this->table('editorial');
        $tableEditorial->addColumn('nombre', 'string', ['limit' => 60])
                       ->create();

        $tableIdioma = $this->table('idioma');
        $tableIdioma->addColumn('nombre', 'string', ['limit' => 60])
                    ->create();

        $tableAutor = $this->table('autor');
        $tableAutor->addColumn('nombre', 'string', ['limit' => 100])
                   ->addColumn('biografia', 'text', ['null' => true])
                   ->create();

        $tableLibro = $this->table('libro');
        $tableLibro->addColumn('imagen', 'string', ['limit' => 255, 'null' => true, 'default' => 'portada.png'])
                   ->addColumn('titulo','string',['limit' => 60])
                   ->addColumn('descripcion','string',['null' => true])
                   ->addColumn('precio','decimal',['precision' => 10, 'scale' => 2])
                   ->addColumn('genero_id','integer', ['signed' => false, 'null' => true])
                   ->addColumn('editorial_id','integer', ['signed' => false, 'null' => true])
                   ->addColumn('idioma_id','integer', ['signed' => false, 'null' => true])
                   ->addColumn('stock','integer',['default' => 0])
                   ->addColumn('autor_id', 'integer', ['signed' => false, 'null' => true])

                   ->addForeignKey('autor_id', 'autor', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
                   ->addForeignKey('genero_id', 'genero', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
                   ->addForeignKey('editorial_id', 'editorial', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
                   ->addForeignKey('idioma_id', 'idioma', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
                   -> create();
    }
}
