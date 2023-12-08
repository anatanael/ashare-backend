<?php


$route = $_SERVER['REQUEST_URI'];
$baseApi = "/api";

if (substr($route, 0, strlen($baseApi)) === $baseApi) {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST");
  header("Access-Control-Allow-Headers: Content-Type");

  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  require_once "api/api.php";
} else {
  require_once("./web/index.html");
}
