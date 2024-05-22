<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pathMigrations = __DIR__ . '/src/Database/migrations';
$pathSeeds = __DIR__ . '/src/Database/seeds';

$env = $_ENV['DEFAULT_ENV'] ?? 'development';

$config = [
  'driver'   => $_ENV['DB_DRIVER_' . strtoupper($env)] ?? null,
  'host'     => $_ENV['DB_HOST_' . strtoupper($env)] ?? null,
  'database' => $_ENV['DB_NAME_' . strtoupper($env)] ?? null,
  'username' => $_ENV['DB_USER_' . strtoupper($env)] ?? null,
  'password' => $_ENV['DB_PASS_' . strtoupper($env)] ?? null,
  'charset'  => $_ENV['DB_CHARSET_' . strtoupper($env)] ?? null,
  'port'     => $_ENV['DB_PORT_' . strtoupper($env)] ?? null,
];

foreach ($config as $key => $value) {
  if ($value === null) {
    throw new Exception('Incomplete database configuration.');
  }
}

return [
  'paths' => [
    'migrations' => $pathMigrations,
    'seeds'      => $pathSeeds,
  ],
  'environments' => [
    'production' => [
      'adapter' => $config['driver'],
      'host'    => $config['host'],
      'name'    => $config['database'],
      'user'    => $config['username'],
      'pass'    => $config['password'],
      'port'    => $config['port'],
      'charset' => $config['charset'],
    ],
    'development' => [
      'adapter' => $config['driver'],
      'host'    => $config['host'],
      'name'    => $config['database'],
      'user'    => $config['username'],
      'pass'    => $config['password'],
      'port'    => $config['port'],
      'charset' => $config['charset'],
    ],
    'testing' => [
      'adapter' => $config['driver'],
      'host'    => $config['host'],
      'name'    => $config['database'],
      'user'    => $config['username'],
      'pass'    => $config['password'],
      'port'    => $config['port'],
      'charset' => $config['charset'],
    ],
  ],
];
