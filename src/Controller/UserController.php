<?php

namespace AshareApp\Controller;

use AshareApp\DTO\User\UserCreateDTO;
use AshareApp\Model\UserModel;
use AshareApp\Repository\UserRepository;
use AshareApp\Util\APIException;
use AshareApp\Validations\FieldValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
  private $userRepository;

  public function __construct()
  {
    $this->userRepository = new UserRepository();
  }

  public function create(Request $request, Response $response)
  {
    $dataRequest = $request->getParsedBody();
    $dataRequest['name'] = trim($dataRequest['name']);
    $dataRequest['email'] = trim($dataRequest['email']);
    $dataRequest['password'] = trim($dataRequest['password']);
    $dataRequest['email'] = trim($dataRequest['email']);

    FieldValidator::checkRequiredFields($dataRequest, [
      'name',
      'username',
      'password',
      'email',
    ]);

    $userModel = new UserModel();
    $userModel->setName($dataRequest['name']);
    $userModel->setUsername($dataRequest['username']);
    $userModel->setEmail($dataRequest['email']);

    if (!filter_var($userModel->getEmail(), FILTER_VALIDATE_EMAIL)) {
      throw new APIException("'email' is invalid");
    }

    $userExist = $this->userRepository->getByUsernameOrEmail($userModel);
    if ($userExist) {
      throw new APIException("'username' or 'email' already exists", 409);
    }

    $userModel->setPassword(password_hash($dataRequest['password'], PASSWORD_DEFAULT));

    $userDB = $this->userRepository->create($userModel);
    $userModel->setId($userDB->id);
    $userModel->setUrlImage($userDB->urlImage);
    $userModel->setCreatedAt($userDB->createdAt);
    $userModel->setUpdatedAt($userDB->updatedAt);

    $userCreateDTO = new UserCreateDTO($userModel);

    $response = $response->withStatus(200);
    $response->getBody()->write(json_encode($userCreateDTO));

    return $response;
  }
}
