<?php

namespace AshareApp\DTO\CategoryDTO;

use AshareApp\DTO\DTO;
use AshareApp\Model\CategoryModel;

class CategoryCreateDTO extends DTO
{
  private $id;
  private $title;
  private $userId;
  private $createdAt;

  public function __construct(CategoryModel $categoryModel)
  {
    $this->id = $categoryModel->getId();
    $this->title = $categoryModel->getTitle();
    $this->userId = $categoryModel->getUserId();
    $this->createdAt = $categoryModel->getCreatedAt();
  }
}
