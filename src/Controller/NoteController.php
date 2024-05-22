<?php

namespace AshareApp\Controller;

use AshareApp\Model\NoteModel;
use AshareApp\Repository\CategoryRepository;
use AshareApp\Repository\NoteRepository;
use AshareApp\Util\APIException;
use AshareApp\Validations\FieldValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class NoteController
{
  private $noteRepository;
  private $categoryRepository;

  public function __construct()
  {
    $this->noteRepository = new NoteRepository();
    $this->categoryRepository = new CategoryRepository();
  }

  public function create(Request $request, Response $response, $args)
  {
    $dataRequest = $request->getParsedBody();
    $userId = $request->getAttribute('userId');
    $categoryId = $args['categoryId'];

    $dataRequest['text'] = trim($dataRequest['text']);
    FieldValidator::checkRequiredFields($dataRequest, [
      'text',
    ]);

    $noteModel = new NoteModel();
    $noteModel->setText($dataRequest['text']);
    $noteModel->setUserId($userId);
    $noteModel->setCategoryId($categoryId);

    if ($categoryId) {
      $ownerCategory = $this->categoryRepository->getUserIdCategory($categoryId);

      if ($ownerCategory != $userId) {
        throw new APIException('User not is owner of this category', 403);
      }

      $noteDB = $this->noteRepository->createByCategory($noteModel);
    } else {
      $noteDB = $this->noteRepository->create($noteModel);
    }

    $response = $response->withStatus(200);
    $response->getBody()->write(json_encode($noteDB));

    return $response;
  }

  public function getAll(Request $request, Response $response)
  {
    $userId = $request->getAttribute('userId');

    if ($userId) {
      $notes = $this->noteRepository->getAllUserId($userId);
    } else {
      $notes = $this->noteRepository->getAllUserIdNull();
    }

    $response = $response->withStatus(200);
    $response->getBody()->write(json_encode($notes));

    return $response;
  }

  public function getAllByCategory(Request $request, Response $response, $args)
  {
    $userId = $request->getAttribute('userId');
    $categoryId = $args['categoryId'];

    $noteModel = new NoteModel();
    $noteModel->setId($categoryId);
    $noteModel->setUserId($userId);
    $noteModel->setCategoryId($categoryId);

    $notes = $this->noteRepository->getAllByCategoryId($noteModel);

    $response = $response->withStatus(200);
    $response->getBody()->write(json_encode($notes));

    return $response;
  }

  public function delete(Request $request, Response $response, $args)
  {
    $nodeId = $args['nodeId'];
    $userId = $request->getAttribute('userId');

    $noteModel = new NoteModel();
    $noteModel->setId($nodeId);
    $noteModel->setUserId($userId);

    if ($noteModel->getUserId()) {
      $isDeleted = $this->noteRepository->deleteByUser($noteModel);
    } else {
      $isDeleted = $this->noteRepository->delete($nodeId);
    }

    if ($isDeleted == 0) {
      throw new APIException('Note not found', 404);
    }

    $response = $response->withStatus(200);

    return $response;
  }
}
