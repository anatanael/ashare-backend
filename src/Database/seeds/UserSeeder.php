<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
  public function run(): void
  {
    $data = [
      [
        'name'     => 'Administrador',
        'username' => 'admin',
        'password' => '$2y$10$aPux5ew9VKUpBOXyow.jceo3tvcd8lTYYf7JKjeCQSQD.jQ0iZFaa',
        'email'    => 'admin@gmail.com',
      ],
    ];

    $posts = $this->table('User');
    $posts->insert($data)
      ->saveData();
  }
}
