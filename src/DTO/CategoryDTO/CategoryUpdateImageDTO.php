<?php

namespace AshareApp\DTO\CategoryDTO;

use AshareApp\DTO\DTO;
use AshareApp\Model\CategoryModel;

class CategoryUpdateImageDTO extends DTO
{
  private $id;
  private $urlImage;
  private $hashDeleteImage;

  public function __construct(CategoryModel $categoryModel)
  {
    $this->id = $categoryModel->getId();
    $this->urlImage = $categoryModel->getUrlImage();
    $this->hashDeleteImage = $categoryModel->getHashDeleteImage();
  }
}
