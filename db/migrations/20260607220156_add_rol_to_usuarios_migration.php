<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddRolToUsuariosMigration extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('usuarios');
        $table->addColumn('rol', 'string', ['limit' => 20, 'default' => 'cliente'])
              ->update();
    }
}
