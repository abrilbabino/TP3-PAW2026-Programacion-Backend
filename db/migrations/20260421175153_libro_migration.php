<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class LibroMigration extends AbstractMigration
{
    public function change(): void
    {
        $tableAutor = $this->table('autor');
        $tableAutor->addColumn('nombre', 'string', ['limit' => 100])
                   ->addColumn('biografia', 'text', ['null' => true])
                   ->create();

        $tableLibro = $this->table('libro');
        $tableLibro->addColumn('titulo','string',['limit' => 60])
                   ->addColumn('descripcion','string',['null' => true])
                   ->addColumn('precio','decimal',['precision' => 10, 'scale' => 2])
                   ->addColumn('genero','string',['limit' => 60])
                   ->addColumn('editorial','string',['limit' => 60])
                   ->addColumn('idioma','string',['limit' => 60])
                   ->addColumn('stock','integer',['default' => 0])
                   ->addColumn('autor_id', 'integer', ['signed' => false, 'null' => true])
                   ->addForeignKey('autor_id', 'autor', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
                   -> create();
    }
}
