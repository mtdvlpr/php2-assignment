<?php
require_once __DIR__ . '/../db/userDB.php';
require_once __DIR__ . '/../model/userModel.php';

class UserController
{
  private UserDB $userDB;

  public function __construct()
  {
    $this->userDB = new UserDB();
  }
}
