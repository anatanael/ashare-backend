<?php

require_once __ROUTER;
require_once __CONTROLLER . "Message.php";

Router::get("/api/message", function () {
  MessageController::getAll();
});

Router::post("/api/message", function () {
  MessageController::create();
});

Router::post("/api/message/delete", function () {
  MessageController::delete();
});