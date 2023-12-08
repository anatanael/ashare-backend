<?php

require_once "setup/global.php";
require_once __ROUTER;

$allRoutes = glob("api/routes/*.php");
foreach ($allRoutes as $currentRoute) {
  require_once $currentRoute;
}

Router::exec();
