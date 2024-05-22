<?php

namespace AshareApp\DTO\User;

use AshareApp\DTO\DTO;
use AshareApp\Model\UserModel;

class UserCreateDTO extends DTO
{
  private $id;
  private $name;
  private $username;
  private $email;
  private $urlImage;
  private $createdAt;

  public function __construct(UserModel $userModel)
  {
    $this->id = $userModel->getId();
    $this->name = $userModel->getName();
    $this->username = $userModel->getUsername();
    $this->email = $userModel->getEmail();
    $this->urlImage = $userModel->getUrlImage();
    $this->createdAt = $userModel->getCreatedAt();
  }
}
