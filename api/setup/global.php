<?php

$baseApi = "api";

$ENV = include "env.php";
define("__ENV", $ENV);

define("__ROUTER", "$baseApi/setup/Router.php");
define("__CONTROLLER", "$baseApi/controller/");
define("__CONNECTION", "$baseApi/database/connection.php");
