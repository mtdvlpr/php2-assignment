<?php
require_once __DIR__ . '/../db/userDB.php';
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/../model/table.php';
require_once __DIR__ . '/../model/field.php';

class AdminController
{
  private UserDB $userDB;

  public function __construct()
  {
    $this->userDB = new UserDB();
  }

  public function getAdminPage(UserModel $user): array
  {
    return [
      "user" => $user,
      "tableModel" => new TableModel($this->userDB->getUsers()),
      "field" => new FieldModel(
        '',
        '',
        '',
        null,
        'select',
        true,
        $this->userDB->getUsers($user->getRole())
      )
    ];
  }
}
