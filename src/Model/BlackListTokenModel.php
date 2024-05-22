<?php

namespace AshareApp\Model;

class BlackListTokenModel
{
  private $refreshToken;
  private $createdAt;
  private $expirationAt;

  public function getRefreshToken()
  {
    return $this->refreshToken;
  }

  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  public function getExpirationAt()
  {
    return $this->expirationAt;
  }

  public function setRefreshToken($refreshToken): void
  {
    $this->refreshToken = $refreshToken;
  }

  public function setCreatedAt($createdAt): void
  {
    $this->createdAt = $createdAt;
  }

  public function setExpirationAt($expirationAt): void
  {
    $this->expirationAt = $expirationAt;
  }
}
