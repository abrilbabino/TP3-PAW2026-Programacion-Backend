<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UsuarioMigration extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('usuarios');
        $table->addColumn('nombre_completo', 'string', ['limit' => 100])
              ->addColumn('email', 'string', ['limit' => 150])
              ->addColumn('usuario', 'string', ['limit' => 60])
              ->addColumn('contrasenia', 'string', ['limit' => 255])
              ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addIndex(['usuario'], ['unique' => true])
              ->addIndex(['email'], ['unique' => true])
              ->create();
    }
}
