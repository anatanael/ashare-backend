<?php

namespace AshareApp\Repository;

use AshareApp\Model\NoteModel;

class NoteRepository extends Repository
{
  private $nameTable = 'Note';

  public function create(NoteModel $noteModel)
  {
    $idNote = $this->database->table($this->nameTable)
      ->insert([
        'text' => $noteModel->getText(),
      ]);

    return $this->database->table($this->nameTable)
      ->where('id', '=', $idNote)
      ->first();
  }

  public function createByCategory(NoteModel $noteModel)
  {
    $idNote = $this->database->table($this->nameTable)
      ->insert([
        'userId'     => $noteModel->getUserId(),
        'categoryId' => $noteModel->getCategoryId(),
        'text'       => $noteModel->getText(),
      ]);

    return $this->database->table($this->nameTable)
      ->where('id', '=', $idNote)
      ->first();
  }

  public function getAllUserIdNull()
  {
    $notes = $this->database->table($this->nameTable)
      ->where('userId', 'IS', null)
      ->get();

    return $notes;
  }

  public function getAllUserId($userId)
  {
    $notes = $this->database->table($this->nameTable)
      ->where('userId', '=', $userId)
      ->get();

    return $notes;
  }

  public function getAllByCategoryId(NoteModel $noteModel)
  {
    return $this->database->table($this->nameTable)
      ->where('userId', '=', $noteModel->getUserId())
      ->where('categoryId', '=', $noteModel->getCategoryId())
      ->get();
  }

  public function deleteByUser(NoteModel $noteModel)
  {
    return $this->database->table($this->nameTable)
      ->where('id', '=', $noteModel->getId())
      ->where('userId', '=', $noteModel->getUserId())
      ->delete()
      ->rowCount();
  }

  public function delete($noteId)
  {
    return $this->database->table($this->nameTable)
      ->where('id', '=', $noteId)
      ->where('userId', 'IS', null)
      ->delete()
      ->rowCount();
  }
}
