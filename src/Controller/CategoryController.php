<?php

namespace AshareApp\Controller;

use AshareApp\DTO\CategoryDTO\CategoryCreateDTO;
use AshareApp\DTO\CategoryDTO\CategoryGetDTO;
use AshareApp\DTO\CategoryDTO\CategoryUpdateImageDTO;
use AshareApp\Model\CategoryModel;
use AshareApp\Repository\CategoryRepository;
use AshareApp\Service\ImgurService;
use AshareApp\Util\APIException;
use AshareApp\Validations\FieldValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategoryController
{
  private $categoryRepository;
  private $imgurService;

  public function __construct()
  {
    $this->categoryRepository = new CategoryRepository();
    $this->imgurService = new ImgurService();
  }

  public function create(Request $request, Response $response)
  {
    $dataRequest = $request->getParsedBody();
    $userId = $request->getAttribute('userId');

    $dataRequest['title'] = trim($dataRequest['title']);
    FieldValidator::checkRequiredFields($dataRequest, [
      'title',
    ]);

    $categoryModel = new CategoryModel();
    $categoryModel->setTitle($dataRequest['title']);
    $categoryModel->setUserId($userId);

    $categoryDB = $this->categoryRepository->create($categoryModel);
    $categoryModel->setId($categoryDB->id);
    $categoryModel->setCreatedAt($categoryDB->createdAt);

    $categoryCreateDTO = new CategoryCreateDTO($categoryModel);

    $response = $response->withStatus(200);
    $response->getBody()->write(json_encode($categoryCreateDTO));

    return $response;
  }

  public function getAllByUserId(Request $request, Response $response)
  {
    $userId = $request->getAttribute('userId');
    $categoryModel = new CategoryModel();
    $categoryModel->setUserId($userId);

    $categoriesDB = $this->categoryRepository->getAllByUserId($categoryModel);

    $categoriesGetDTO = array_map(function ($category) {
      $categoryModelCurrent = new CategoryModel();
      $categoryModelCurrent->setId($category->id);
      $categoryModelCurrent->setTitle($category->title);
      $categoryModelCurrent->setHashDeleteImage($category->hashDeleteImage);
      $categoryModelCurrent->setUrlImage($category->urlImage);
      $categoryModelCurrent->setCreatedAt($category->createdAt);
      $categoryModelCurrent->setUpdatedAt($category->updatedAt);

      return new CategoryGetDTO($categoryModelCurrent);
    }, $categoriesDB);

    $response = $response->withStatus(200);
    $response->getBody()->write(json_encode($categoriesGetDTO));

    return $response;
  }

  public function delete(Request $request, Response $response, $args)
  {
    $userId = $request->getAttribute('userId');
    $categoryId = $args['id'];

    $categoryModel = new CategoryModel();
    $categoryModel->setId($categoryId);
    $categoryModel->setUserId($userId);

    $isDeleted = $this->categoryRepository->delete($categoryModel);

    if (!$isDeleted) {
      throw new APIException('Category not found', 404);
    }

    $response = $response->withStatus(200);

    return $response;
  }

  public function updateImage(Request $request, Response $response, $args)
  {
    $userId = $request->getAttribute('userId');
    $idCategory = $args['id'];
    $files = $request->getUploadedFiles();

    $cover = $files['cover'];
    FieldValidator::checkRequiredFields($files, [
      'cover',
    ]);

    if (!is_numeric($idCategory)) {
      throw new APIException('Category invalid', 404);
    }

    $responseImg = $this->imgurService->uploadImage($cover);

    if (!$responseImg || $responseImg['status'] != 200) {
      throw new APIException('Error uploading image', 500);
    }

    $urlImage = $responseImg['data']['link'];
    $hashDeleteImage = $responseImg['data']['deletehash'];

    $categoryModel = new CategoryModel();
    $categoryModel->setId($idCategory);
    $categoryModel->setUserId($userId);
    $categoryModel->setUrlImage($urlImage);
    $categoryModel->setHashDeleteImage($hashDeleteImage);

    $updatedCategory = $this->categoryRepository->updateImage($categoryModel);

    if ($updatedCategory == 1) {
      $categoryDTO = new CategoryUpdateImageDTO($categoryModel);

      $response = $response->withStatus(200);
      $response->getBody()->write(json_encode($categoryDTO));
    } else {
      $response = $response->withStatus(404);
    }

    return $response;
  }
}
