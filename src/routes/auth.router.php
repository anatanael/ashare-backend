<?php

use AshareApp\Controller\AuthController;

return function ($app) {
  $app->post('/login', [AuthController::class, 'login']);
  $app->post('/refreshToken', [AuthController::class, 'refreshToken']);
  $app->post('/validateToken', [AuthController::class, 'validateToken']);
};
