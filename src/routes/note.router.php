<?php

use AshareApp\Config\Authenticate;
use AshareApp\Controller\NoteController;

return function ($app) {
  $app->post('/note', [NoteController::class, 'create']);
  $app->post('/user/note', [NoteController::class, 'create'])->add(new Authenticate());
  $app->post('/user/note/category/{categoryId}', [NoteController::class, 'create'])->add(new Authenticate());

  $app->get('/note', [NoteController::class, 'getAll']);
  $app->get('/user/note', [NoteController::class, 'getAll'])->add(new Authenticate());
  $app->get('/user/note/category/{categoryId}', [NoteController::class, 'getAllByCategory'])->add(new Authenticate());

  $app->delete('/note/{nodeId}', [NoteController::class, 'delete']);
  $app->delete('/user/note/{nodeId}', [NoteController::class, 'delete'])->add(new Authenticate());
};
