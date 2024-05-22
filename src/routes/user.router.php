<?php

use AshareApp\Controller\UserController;

return function ($app) {
  $app->post('/user', [UserController::class, 'create']);
};
