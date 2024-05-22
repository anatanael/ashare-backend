<?php

namespace AshareApp\Controller;

use AshareApp\Config\Authenticate;
use AshareApp\Model\UserModel;
use AshareApp\Repository\UserRepository;
use AshareApp\Util\APIException;
use AshareApp\Validations\FieldValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
  private $userRepository;
  private $authenticate;

  public function __construct()
  {
    $this->userRepository = new UserRepository();
    $this->authenticate = new Authenticate();
  }

  public function login(Request $request, Response $response)
  {
    $data = $request->getParsedBody();

    FieldValidator::checkRequiredFields($data, [
      'username',
      'password',
    ]);

    $userModel = new UserModel();
    $userModel->setUsername($data['username']);
    $userModel->setPassword($data['password']);

    $userDB = $this->userRepository->getByUsernameOrEmail($userModel);
    if (!$userDB) {
      throw new APIException("'username' or 'password' is invalid", 401);
    }
    $userModel->setId($userDB->id);
    $userModel->setName($userDB->name);
    $userModel->setUsername($userDB->username);
    $userModel->setEmail($userDB->email);

    if (!password_verify($userModel->getPassword(), $userDB->password)) {
      throw new APIException("'username' or 'password' is invalid", 401);
    }

    $payload = [
      'id'       => $userModel->getId(),
      'username' => $userModel->getUsername(),
      'email'    => $userModel->getEmail(),
    ];

    $accessToken = $this->authenticate->generateAccessToken($payload);
    $refreshToken = $this->authenticate->generateRefreshToken($payload);

    setcookie('refreshToken', $refreshToken, time() + (86400 * 30), '/', '', true, true);

    $response = $response->withStatus(200);
    $response->getBody()->write(json_encode([
      'accessToken'  => $accessToken,
      'refreshToken' => $refreshToken,
      'user'         => [
        'name' => $userModel->getName(),
      ],
    ]));

    return $response;
  }

  public function refreshToken(Request $request, Response $response)
  {
    $data = $request->getParsedBody();

    FieldValidator::checkRequiredFields($data, [
      'token',
    ]);

    $refreshToken = $data['token'];

    $dataToken = $this->authenticate->validateJWTRefreshToken($refreshToken);
    if (!$dataToken) {
      throw new APIException('Error validating token', 401);
    }

    if ($this->authenticate->isBlackListToken($refreshToken)) {
      $messageError = 'Error validating token';

      if ($_ENV['DEFAULT_ENV'] == 'development') {
        $messageError .= ', expired token';
      }

      throw new APIException($messageError, 401);
    }

    $this->authenticate->invalidToken($refreshToken, $dataToken);

    $newAccessToken = $this->authenticate->generateAccessToken($dataToken);
    $newRefreshToken = $this->authenticate->generateRefreshToken($dataToken);

    $response = $response->withStatus(200);
    $response->getBody()->write(json_encode(
      ['accessToken'   => $newAccessToken,
        'refreshToken' => $newRefreshToken,
      ]
    ));

    return $response;
  }

  public function validateToken(Request $request, Response $response)
  {
    $data = $request->getParsedBody();

    FieldValidator::checkRequiredFields($data, [
      'token',
    ]);

    $accessToken = $data['token'];

    $dataToken = $this->authenticate->validateJWTAccessToken($accessToken);
    if (!$dataToken) {
      throw new APIException('Error validating token', 401);
    }

    $response = $response->withStatus(200);

    return $response;
  }
}
