<?php

namespace AshareApp\Controller;

use AshareApp\Config\Authenticate;
use AshareApp\Repository\UserRepository;
use AshareApp\Util\APIException;
use AshareApp\Validations\FieldValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BlackListTokenController
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

    $username = $data['username'];
    $password = $data['password'];

    $user = $this->userRepository->getByUsernameOrEmail($username);
    if (!$user) {
      throw new APIException("'username' or 'password' is invalid", 401);
    }

    if (!password_verify($password, $user->password)) {
      throw new APIException("'username' or 'password' is invalid", 401);
    }

    $payload = [
      'id'       => $user->id,
      'username' => $user->username,
      'email'    => $user->email,
    ];

    $accessToken = $this->authenticate->generateAccessToken($payload);
    $refreshToken = $this->authenticate->generateRefreshToken($payload);

    $response = $response->withStatus(200);
    $response->getBody()->write(json_encode(['accessToken' => $accessToken, 'refreshToken' => $refreshToken]));

    return $response;
  }

  public function refreshToken(Request $request, Response $response)
  {
    $data = $request->getParsedBody();

    FieldValidator::checkRequiredFields($data, [
      'token',
    ]);

    $refreshToken = $data['token'];

    if ($this->authenticate->validateJWTRefreshToken($refreshToken)) {
      $response->getBody()->write(json_encode(['refreshToken' => $refreshToken]));
      $response = $response->withStatus(200);
    } else {
      $response = $response->withStatus(401);
    }

    return $response;
  }
}
