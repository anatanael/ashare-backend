<?php

use AshareApp\Config\Authenticate;
use AshareApp\Controller\CategoryController;

return function ($app) {
  $app->get('/user/category', [CategoryController::class, 'getAllByUserId'])->add(new Authenticate());

  $app->delete('/user/category/{id}', [CategoryController::class, 'delete'])->add(new Authenticate());

  $app->post('/user/category', [CategoryController::class, 'create'])->add(new Authenticate());
  $app->post('/user/category/{id}/image', [CategoryController::class, 'updateImage'])->add(new Authenticate());
};
