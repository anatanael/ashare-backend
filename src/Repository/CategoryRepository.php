<?php

namespace AshareApp\Repository;

use AshareApp\Model\CategoryModel;

class CategoryRepository extends Repository
{
  private $nameTable = 'Category';

  public function create(CategoryModel $categoryModel)
  {
    $idCategory = $this->database
      ->table($this->nameTable)
      ->insert([
        'title'  => $categoryModel->getTitle(),
        'userId' => $categoryModel->getUserId(),
      ]);

    return $this->database
      ->table($this->nameTable)
      ->where('id', '=', $idCategory)
      ->first();
  }

  public function getAllByUserId(CategoryModel $categoryModel)
  {
    return $this->database
      ->table($this->nameTable)
      ->where('userId', '=', $categoryModel->getUserId())
      ->get();
  }

  public function delete(CategoryModel $categoryModel)
  {
    return $this->database
      ->table($this->nameTable)
      ->where('id', '=', $categoryModel->getId())
      ->where('userId', '=', $categoryModel->getUserId())
      ->delete()
      ->rowCount();
  }

  public function getUserIdCategory($userId)
  {
    $category = $this->database
      ->table($this->nameTable)
      ->where('id', '=', $userId)
      ->first();

    return $category->userId;
  }

  public function updateImage(CategoryModel $categoryModel)
  {
    $query = $this->database
      ->table($this->nameTable)
      ->where('id', $categoryModel->getId())
      ->where('userId', $categoryModel->getUserId())
      ->update([
        'urlImage'        => $categoryModel->getUrlImage(),
        'hashDeleteImage' => $categoryModel->getHashDeleteImage(),
        'updatedAt'       => date('Y-m-d H:i:s'),
      ]);

    return $this->rowCountAfterUpdate($query);
  }
}
