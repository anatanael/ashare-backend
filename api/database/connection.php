<?php

$servername = __ENV["DB_SERVE_NAME"];
$username = __ENV["DB_USERNAME"];
$password = __ENV["DB_PASSWORD"];
$db_name = __ENV["DB_NAME"];

$connect = mysqli_connect($servername, $username, $password, $db_name);

if (mysqli_connect_error()) {
  throw new Exception("Connection Fail");
}
