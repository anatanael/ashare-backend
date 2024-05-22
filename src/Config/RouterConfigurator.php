<?php

namespace AshareApp\Config;

use AshareApp\Util\APIException;
use AshareApp\Validations\FieldValidationException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;

class RouterConfigurator
{
  private $app;
  private $routesDirectory;

  public function __construct($app, $routesDirectory)
  {
    $this->app = $app;
    $this->routesDirectory = $routesDirectory;

    $this->initialize();
    $this->loadRoutes();
    $this->loadErrorHandlers();
  }

  public static function configurator($app, $routesDirectory)
  {
    return new self($app, $routesDirectory);
  }

  public function initialize()
  {
    $this->app->addBodyParsingMiddleware();

    $this->app->add(function ($request, $handler) {
      $response = $handler->handle($request);

      return $response->withHeader('Content-Type', 'application/json');
    });
  }

  public function loadRoutes()
  {
    $files = scandir($this->routesDirectory);

    foreach ($files as $file) {
      if (is_file($this->routesDirectory . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        $routeDefinition = require $this->routesDirectory . '/' . $file;
        $routeDefinition($this->app);
      }
    }
  }

  public function loadErrorHandlers()
  {
    $errorMiddleware = $this->app->addErrorMiddleware(true, true, true);

    $errorMiddleware->setErrorHandler(FieldValidationException::class, function (
      $request,
      $exception
    ) {
      $response = new Response();
      $response = $response->withStatus(400);

      $errorMessage = $exception->getMessage();
      $response->getBody()->write($errorMessage);

      return $response;
    });

    $errorMiddleware->setErrorHandler(APIException::class, function (
      $request,
      $exception
    ) {
      $response = new Response();
      $httpCode = $exception->getCode();
      $response = $response->withStatus($httpCode == 0 ? 400 : $httpCode);

      $errorMessage = $exception->getMessage();
      $response->getBody()->write(json_encode(['error' => $errorMessage]));

      return $response;
    });

    $errorMiddleware->setErrorHandler(HttpNotFoundException::class, function (
      $request,
      $exception
    ) {
      $response = new Response();
      $response = $response->withStatus(404);

      return $response;
    });

    $errorMiddleware->setDefaultErrorHandler(function (
      $request,
      $exception
    ) {
      $response = new Response();

      if ($_ENV['DEFAULT_ENV'] == 'development') {
        $errorMessage = $exception->getMessage();
        echo $errorMessage;
      }

      $response = $response->withStatus(500);

      return $response;
    });
  }
}
