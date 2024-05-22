<?php

use AshareApp\Config\RouterConfigurator;
use Slim\Factory\AppFactory;

$pathRoutes = __DIR__ . '/../routes';

$app = AppFactory::create();

RouterConfigurator::configurator($app, $pathRoutes);

$app->run();
