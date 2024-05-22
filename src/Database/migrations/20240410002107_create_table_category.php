<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableCategory extends AbstractMigration
{
  public function change(): void
  {
    $this->table('Category')
      ->addColumn('title', 'string', ['null' => false])
      ->addColumn('urlImage', 'string')
      ->addColumn('hashDeleteImage', 'string')
      ->addColumn('userId', 'integer', ['null' => false, 'signed' => false])
      ->addColumn('createdAt', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
      ->addColumn('updatedAt', 'datetime')
      ->addForeignKey('userId', 'User', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
      ->create();
  }
}
