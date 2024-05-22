<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableUser extends AbstractMigration
{
  public function change(): void
  {
    $this->table('User')
      ->addColumn('name', 'string', ['null' => false])
      ->addColumn('username', 'string', ['null' => false])
      ->addColumn('password', 'string', ['null' => false])
      ->addColumn('email', 'string', ['null' => false])
      ->addColumn('urlImage', 'string')
      ->addColumn('createdAt', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
      ->addColumn('updatedAt', 'datetime')
      ->addIndex(['username'], ['unique' => true])
      ->addIndex(['email'], ['unique' => true])
      ->create();
  }
}
