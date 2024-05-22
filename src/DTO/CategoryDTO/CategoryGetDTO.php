<?php

namespace AshareApp\DTO\CategoryDTO;

use AshareApp\DTO\DTO;
use AshareApp\Model\CategoryModel;

class CategoryGetDTO extends DTO
{
  private $id;
  private $title;
  private $urlImage;
  private $hashDeleteImage;
  private $createdAt;
  private $updatedAt;

  public function __construct(CategoryModel $categoryModel)
  {
    $this->id = $categoryModel->getId();
    $this->title = $categoryModel->getTitle();
    $this->urlImage = $categoryModel->getUrlImage();
    $this->hashDeleteImage = $categoryModel->getHashDeleteImage();
    $this->createdAt = $categoryModel->getCreatedAt();
    $this->updatedAt = $categoryModel->getUpdatedAt();
  }
}
