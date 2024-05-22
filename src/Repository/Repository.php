<?php

namespace AshareApp\Repository;

use AshareApp\Database\Database;

class Repository
{
  protected $database;

  public function __construct()
  {
    $database = new Database();
    $this->database = $database->getDatabase();
  }

  protected function rowCountAfterUpdate($query)
  {
    return $query->rowCount();
  }
}
