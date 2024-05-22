<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableBlackListToken extends AbstractMigration
{
  public function change(): void
  {
    $table = $this->table('BlackListToken', ['id' => false, 'primary_key' => 'refreshToken']);

    $table->addColumn('refreshToken', 'string', ['null' => false])
      ->addColumn('createdAt', 'datetime', ['null' => false])
      ->addColumn('expirationAt', 'datetime', ['null' => false])
      ->addColumn('revokedAt', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
      ->create();
  }
}
