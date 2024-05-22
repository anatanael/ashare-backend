<?php

namespace AshareApp\Database;

use Exception;
use Pixie\Connection;
use Pixie\QueryBuilder\QueryBuilderHandler;

class Database
{
  private $config;

  public function __construct()
  {
    $this->loadConfig();
  }

  private function loadConfig()
  {
    $env = $_ENV['DEFAULT_ENV'] ?? 'development';

    $this->config = [
      'driver'    => $_ENV['DB_DRIVER_' . strtoupper($env)] ?? null,
      'host'      => $_ENV['DB_HOST_' . strtoupper($env)] ?? null,
      'database'  => $_ENV['DB_NAME_' . strtoupper($env)] ?? null,
      'username'  => $_ENV['DB_USER_' . strtoupper($env)] ?? null,
      'password'  => $_ENV['DB_PASS_' . strtoupper($env)] ?? null,
      'charset'   => $_ENV['DB_CHARSET_' . strtoupper($env)] ?? null,
      'collation' => $_ENV['DB_COLLATION_' . strtoupper($env)] ?? null,
      'port'      => $_ENV['DB_PORT_' . strtoupper($env)] ?? null,
    ];
  }

  public function getDatabase(): QueryBuilderHandler
  {
    if (!$this->validateConfig()) {
      throw new Exception('Incomplete database configuration.');
    }

    try {
      $conn = new Connection($this->config['driver'], $this->config);

      return new QueryBuilderHandler($conn);
    } catch (Exception $e) {
      throw new Exception('Failed to connect to the database: ' . $e->getMessage());
    }
  }

  private function validateConfig(): bool
  {
    foreach ($this->config as $key => $value) {
      if ($value === null) {
        return false;
      }
    }

    return true;
  }
}
