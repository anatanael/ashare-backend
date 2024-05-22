<?php

namespace AshareApp\Model;

class CategoryModel
{
  private $id;
  private $title;
  private $userId;
  private $urlImage;
  private $createdAt;
  private $updatedAt;
  private $hashDeleteImage;

  public function getId()
  {
    return $this->id;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getUserId()
  {
    return $this->userId;
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

  public function getHashDeleteImage()
  {
    return $this->hashDeleteImage;
  }

  public function setId($id): void
  {
    $this->id = $id;
  }

  public function setTitle($title): void
  {
    $this->title = $title;
  }

  public function setUserId($userId): void
  {
    $this->userId = $userId;
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

  public function setHashDeleteImage($hashDeleteImage): void
  {
    $this->hashDeleteImage = $hashDeleteImage;
  }
}
