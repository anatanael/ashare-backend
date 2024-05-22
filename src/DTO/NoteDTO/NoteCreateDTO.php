<?php

namespace AshareApp\DTO\NoteDTO;

use AshareApp\DTO\DTO;
use AshareApp\Model\NoteModel;

class NoteCreateDTO extends DTO
{
  private $id;
  private $categoryId;
  private $userId;
  private $text;
  private $createdAt;

  public function __construct(NoteModel $noteModel)
  {
    $this->id = $noteModel->getId();
    $this->categoryId = $noteModel->getCategoryId();
    $this->userId = $noteModel->getUserId();
    $this->text = $noteModel->getText();
    $this->createdAt = $noteModel->getCreatedAt();
  }
}
