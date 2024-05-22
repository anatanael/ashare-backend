<?php

namespace AshareApp\Repository;

use AshareApp\Model\BlackListTokenModel;

class BlackListTokenRepository extends Repository
{
  private $nameTable = 'BlackListToken';

  public function create(BlackListTokenModel $blackListTokenModel)
  {
    $id = $this->database
      ->table($this->nameTable)
      ->insert([
        'refreshToken' => $blackListTokenModel->getRefreshToken(),
        'createdAt'    => $blackListTokenModel->getCreatedAt(),
        'expirationAt' => $blackListTokenModel->getExpirationAt(),
      ]);

    return $id;
  }

  public function getByRefreshToken($refreshToken)
  {
    return $this->database
      ->table($this->nameTable)
      ->where('refreshToken', $refreshToken)
      ->first();
  }
}
