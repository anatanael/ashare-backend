<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableNote extends AbstractMigration
{
  public function change(): void
  {
    $this->table('Note')
      ->addColumn('categoryId', 'integer', ['signed' => false])
      ->addColumn('userId', 'integer', ['signed' => false])
      ->addColumn('text', 'text', ['null' => false])
      ->addColumn('createdAt', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
      ->addColumn('updatedAt', 'datetime')
      ->addForeignKey('categoryId', 'Category', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
      ->addForeignKey('userId', 'User', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
      ->create();
  }
}
