<?php

namespace AshareApp\Model;

class UserModel
{
  private $id;
  private $name;
  private $username;
  private $password;
  private $email;
  private $urlImage;
  private $createdAt;
  private $updatedAt;

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getUrlImage()
  {
    return $this->urlImage;
  }

  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  public function getUpdatedAt()
  {
    return $this->updatedAt;
  }

  public function setId($id): void
  {
    $this->id = $id;
  }

  public function setName($name): void
  {
    $this->name = $name;
  }

  public function setUsername($username): void
  {
    $this->username = $username;
  }

  public function setPassword($password): void
  {
    $this->password = $password;
  }

  public function setEmail($email): void
  {
    $this->email = $email;
  }

  public function setUrlImage($urlImage): void
  {
    $this->urlImage = $urlImage;
  }

  public function setCreatedAt($createdAt): void
  {
    $this->createdAt = $createdAt;
  }

  public function setUpdatedAt($updatedAt): void
  {
    $this->updatedAt = $updatedAt;
  }
}
