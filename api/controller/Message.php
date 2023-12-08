<?php

require_once __ROUTER;
require_once __CONNECTION;

class MessageController
{
  public static function create()
  {
    $value = Request::getBody("text");
    $value = trim($value);

    if (!$value) {
      Response::newError("Field 'text' is required");
      Response::json(422);
    } else {
      try {
        $value = addslashes(htmlspecialchars($value));

        global $connect;

        $sql = "INSERT INTO `message`(`text`) VALUES ('$value')";
        mysqli_query($connect, $sql);
        $id = mysqli_insert_id($connect);

        mysqli_close($connect);

        Response::json(201, $id);
      } catch (Exception $e) {
        Response::json(500);
      }
    }
  }

  public static function getAll()
  {
    try {
      global $connect;

      $sql = "SELECT id, text, created_at from message";

      $resultSql = mysqli_query($connect, $sql);

      $messages = [];
      while ($row = mysqli_fetch_assoc($resultSql)) {
        array_push($messages, $row);
      }
      mysqli_close($connect);

      print_r($messages);

      Response::json(200, $messages);
    } catch (Exception $e) {
      echo $e;
      Response::json(500);
    }
  }

  public static function delete()
  {
    try {
      global $connect;
      $id = Request::getBody("id");

      if (!$id) {
        Response::newError("Field 'id' is required");
        Response::json(422, $id . "asas");
      }

      $sql = "DELETE FROM `message` WHERE `id` = '$id'";
      mysqli_query($connect, $sql);

      $affectedRows = mysqli_affected_rows($connect);
      mysqli_close($connect);
      if ($affectedRows > 0) {
        Response::json(200);
      } else {
        Response::json(404, null, "Id not exist");
      }
      mysqli_close($connect);
    } catch (Exception $e) {
      Response::json(500);
    }
  }
}
