<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ReservaMigration extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('reservas');
        $table->addColumn('nombre', 'string', ['limit' => 100])
              ->addColumn('email', 'string', ['limit' => 150])
              ->addColumn('telefono', 'string', ['limit' => 30])
              ->addColumn('cantidad', 'integer')
              ->addColumn('libro', 'string', ['limit' => 200])
              ->addColumn('fecha', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();
    }
}
