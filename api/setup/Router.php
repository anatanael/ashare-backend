<?php

class Router extends Request
{
  private static $routes = [];

  private static function formateRoute(string $route): string
  {
    $route = trim($route);

    while (strpos($route, "//") !== false) {
      $route = str_replace("//", "/", $route);
    }

    if ($route != "/") {
      // Remove first bar
      $route = substr($route, 1);
      // Remove last bar
      if (substr($route, -1) == '/') {
        $route = substr($route, 0, -1);
      }
    }

    return $route;
  }

  private static function getRequestPath(): string
  {
    $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return self::formateRoute($route);
  }


  private static function filterRouteByPathAndMethod($arrayRoute, $path, $method)
  {
    $filteredRoutes = array_values(
      array_filter($arrayRoute, function ($route) use ($path, $method) {
        return $route->getPath() === $path && $route->getMethod() === $method;
      })
    );

    return empty($filteredRoutes) ? null : $filteredRoutes[0];
  }

  private static function findExactRoute($arrayRoute, $routeSearch)
  {
    $routeMatchIndex = array_search($routeSearch, array_column($arrayRoute, "path"));

    if ($routeMatchIndex !== false) {
      return $arrayRoute[$routeMatchIndex];
    }

    return false;
  }

  private static function splitRouteIntoArray($route)
  {
    return explode("/", $route);
  }

  private static function countRouteSegments($route): int
  {
    $routeSegments = self::splitRouteIntoArray($route);
    return count($routeSegments);
  }

  private static function filterRouteByQtdElements($arrayRoute, int $qtdSegments)
  {
    return array_filter($arrayRoute, function ($route) use ($qtdSegments) {
      return $route->getSegmentsCount() == $qtdSegments;
    });
  }

  private static function findRouteWithParams($arrayRoute, $routeSearch)
  {
    $searchRouteElements = self::splitRouteIntoArray($routeSearch);
    $searchRouteElementsCount = count($searchRouteElements);
    $matchedRoutesByQtd = self::filterRouteByQtdElements($arrayRoute, $searchRouteElementsCount);

    foreach ($matchedRoutesByQtd as $currentRoute) {
      $currentRouteElements = self::splitRouteIntoArray($currentRoute->getPath());

      $routeIsMatch = true;
      $routeParams = [];

      foreach (array_combine($currentRouteElements, $searchRouteElements) as $elementCurrentRoute => $elementSearchRoute) {
        // verify first character
        $elementIsRequired = $elementCurrentRoute[0] !== ":";

        if ($elementIsRequired && $elementCurrentRoute != $elementSearchRoute) {
          $routeIsMatch = false;
          break;
        } else if (!$elementIsRequired) {
          $keyParam = ltrim($elementCurrentRoute, ":");
          $routeParams[$keyParam] = $elementSearchRoute;
        }
      }

      if ($routeIsMatch) {
        $currentRoute->setParams($routeParams);
        return $currentRoute;
      }
    }

    return false;
  }

  private static function findRoute($method)
  {
    $routeInput = self::getRequestPath();
    $filteredRoutesByMethod = self::getRoutesByMethod(self::$routes, $method);

    $routeMatch = self::findExactRoute($filteredRoutesByMethod, $routeInput);
    if (!$routeMatch) {
      $routeMatch = self::findRouteWithParams($filteredRoutesByMethod, $routeInput);
    }

    return $routeMatch;
  }

  private static function addRoute($path, $controller, $method = "GET")
  {
    $path = self::formateRoute($path);

    $countRouteSegments = self::countRouteSegments($path);

    $foundRoute = self::filterRouteByPathAndMethod(self::$routes, $path, $method);

    if ($foundRoute) {
      $foundRoute->setController($controller);
    } else {
      array_push(self::$routes, new RouterModel(
        $method,
        $path,
        $controller,
        $countRouteSegments
      ));
    }
  }

  private static function getRoutesByMethod($routes, $method)
  {
    return array_filter($routes, function ($route) use ($method) {
      return $route->getMethod() == $method;
    });
  }

  public static function get($route, $controller)
  {
    self::addRoute($route, $controller, "GET");
  }

  public static function post($route, $controller)
  {
    self::addRoute($route, $controller, "POST");
  }

  public static function put($route, $controller)
  {
    self::addRoute($route, $controller, "PUT");
  }

  public static function delete($route, $controller)
  {
    self::addRoute($route, $controller, "DELETE");
  }

  private static function execController($route)
  {
    $route->getController()();
  }

  public static function exec()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $routeMatch = self::findRoute($requestMethod);

    if ($routeMatch) {
      $varRequestMethod = "_" . $requestMethod;
      $$varRequestMethod = json_decode(file_get_contents("php://input"), true);

      self::$body = $$varRequestMethod;
      self::$params = $routeMatch->getParams();

      self::execController($routeMatch);
    } else {
      Response::json(404);
    }
  }
}

class RouterModel
{
  private $method;
  private $path;
  private $controller;
  private $segmentsCount;
  private $params = [];
  public function __construct($method, $path, $controller, $segmentsCount)
  {
    $this->method = $method;
    $this->path = $path;
    $this->controller = $controller;
    $this->segmentsCount = $segmentsCount;
  }

  public function getMethod()
  {
    return $this->method;
  }
  public function setMethod($method)
  {
    $this->method = $method;
  }

  public function getPath()
  {
    return $this->path;
  }
  public function setPath($path)
  {
    $this->path = $path;
  }

  public function getController()
  {
    return $this->controller;
  }
  public function setController($controller)
  {
    $this->controller = $controller;
  }

  public function getSegmentsCount()
  {
    return $this->segmentsCount;
  }
  public function setSegmentsCount($segmentsCount)
  {
    $this->segmentsCount = $segmentsCount;
  }

  public function getParams()
  {
    return $this->params;
  }
  public function setParams($params)
  {
    $this->params = $params;
  }
}

class Request
{
  protected static $body = [];
  protected static $params = [];

  public static function getBody($key = false)
  {
    if (!$key) {
      return self::$body;
    } else {
      return isset(self::$body[$key]) ? self::$body[$key] : false;
    }
  }

  public static function getParams()
  {
    return self::$params;
  }
}

class Response
{
  public $handleRouteError;

  private static $errors = [];

  public static function newError(string $error)
  {
    array_push(self::$errors, $error);
  }

  public static function json($status = 200, $payload = null, $message = null)
  {
    $response = [];

    if ($payload) {
      $response["payload"] = $payload;
    }

    if ($message) {
      $response["message"] = $message;
    }

    if (!empty(self::$errors)) {
      $response["errors"] = self::$errors;
    }

    http_response_code($status);

    if ($response != []) {
      header("Content-Type: application/json");
      echo json_encode($response);
    }

    exit();
  }
}
