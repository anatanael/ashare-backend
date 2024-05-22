<?php

namespace AshareApp\Model;

class NoteModel
{
  private $id;
  private $userId;
  private $categoryId;
  private $text;
  private $createdAt;
  private $updatedAt;

  public function getId()
  {
    return $this->id;
  }

  public function getUserId()
  {
    return $this->userId;
  }

  public function getCategoryId()
  {
    return $this->categoryId;
  }

  public function getText()
  {
    return $this->text;
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

  public function setUserId($userId): void
  {
    $this->userId = $userId;
  }

  public function setCategoryId($categoryId): void
  {
    $this->categoryId = $categoryId;
  }

  public function setText($text): void
  {
    $this->text = $text;
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
