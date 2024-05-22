<?php

namespace AshareApp\Repository;

use AshareApp\Model\UserModel;

class UserRepository extends Repository
{
  private $nameTable = 'User';

  public function create(UserModel $userModel)
  {
    $idUser = $this->database->table($this->nameTable)->insert([
      'name'     => $userModel->getName(),
      'email'    => $userModel->getEmail(),
      'username' => $userModel->getUsername(),
      'password' => $userModel->getPassword(),
    ]);

    $user = $this->database
      ->table($this->nameTable)
      ->where('id', '=', $idUser)
      ->first();

    return $user;
  }

  public function getByUsernameOrEmail(UserModel $userModel)
  {
    return $this->database
      ->table($this->nameTable)
      ->where('username', $userModel->getUsername())
      ->orWhere('email', $userModel->getEmail())
      ->first();
  }
}
