<?php

namespace AshareApp\Config;

use AshareApp\Model\BlackListTokenModel;
use AshareApp\Repository\BlackListTokenRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class Authenticate
{
  private $JWT_ACCESS_TOKEN;
  private $JWT_REFRESH_TOKEN;
  private $blackListTokenRepository;
  private $timeExpirationAccessToken;
  private $timeExpirationRefreshToken;

  public function __construct()
  {
    $this->JWT_ACCESS_TOKEN = $_ENV['JWT_ACCESS_TOKEN'];
    $this->JWT_REFRESH_TOKEN = $_ENV['JWT_REFRESH_TOKEN'];
    $this->blackListTokenRepository = new BlackListTokenRepository();
  }

  public function __invoke(Request $request, RequestHandlerInterface $handler): Response
  {
    $response = new Response();
    $token = $request->getHeaderLine('Authorization');

    if (empty($token)) {
      $response->getBody()->write(json_encode(['error' => 'No token provided']));

      return $response->withStatus(401);
    }

    $dataToken = $this->validateJWTAccessToken($token);
    if (!$dataToken) {
      $response->getBody()->write(json_encode(['error' => 'Invalid token']));

      return $response->withStatus(401);
    }

    $userId = $dataToken['id'];
    $request = $request->withAttribute('userId', $userId);

    return $handler->handle($request);
  }

  public function validateJWTAccessToken($token)
  {
    return $this->validateJWT($token, $this->JWT_ACCESS_TOKEN);
  }

  public function validateJWTRefreshToken($token)
  {
    return $this->validateJWT($token, $this->JWT_REFRESH_TOKEN);
  }

  private function validateJWT($token, $jwtKey)
  {
    try {
      $tokenDecoded = JWT::decode($token, new Key($jwtKey, 'HS256'));
      $tokenDecoded = get_object_vars($tokenDecoded);

      $current_time = time();
      if ($current_time >= $tokenDecoded['iat'] && $current_time <= $tokenDecoded['exp']) {
        return $tokenDecoded;
      } else {
        return false;
      }
    } catch (\Exception $e) {
      return false;
    }
  }

  public function generateAccessToken($payload)
  {
    $this->timeExpirationAccessToken = 60 * 60 * 2;

    return $this->generateJWT($payload, $this->JWT_ACCESS_TOKEN, $this->timeExpirationAccessToken);
  }

  public function generateRefreshToken($payload)
  {
    $this->timeExpirationRefreshToken = 60 * 60 * 24 * 30;

    return $this->generateJWT(
      $payload,
      $this->JWT_REFRESH_TOKEN,
      $this->timeExpirationRefreshToken
    );
  }

  private function generateJWT($payload, $JWT_KEY, $expiration)
  {
    $payload['iat'] = time();
    $payload['exp'] = time() + $expiration;

    $tokenJWT = JWT::encode($payload, $JWT_KEY, 'HS256');

    return $tokenJWT;
  }

  public function invalidToken($token, $dataToken)
  {
    $tokenCreatedAt = date('Y-m-d H:i:s', $dataToken['iat']);
    $tokenExpirationAt = date('Y-m-d H:i:s', $dataToken['exp']);

    $blackListTokenModel = new BlackListTokenModel();
    $blackListTokenModel->setRefreshToken($token);
    $blackListTokenModel->setCreatedAt($tokenCreatedAt);
    $blackListTokenModel->setExpirationAt($tokenExpirationAt);

    $this->blackListTokenRepository->create($blackListTokenModel);
  }

  public function isBlackListToken($token)
  {
    return $this->blackListTokenRepository->getByRefreshToken($token);
  }

  public function getTimeExpirationAccessToken()
  {
    return $this->timeExpirationAccessToken;
  }

  public function getTimeExpirationRefreshToken()
  {
    return $this->timeExpirationRefreshToken;
  }
}
