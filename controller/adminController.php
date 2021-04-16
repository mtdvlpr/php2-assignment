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

  public function getAdminPage(UserModel $user, string $searchMail = '', string $searchName = '', string $regDate = ''): array
  {
    return [
      "user" => $user,
      "tableModel" => new TableModel($this->userDB->getUsers(3, $searchName, $searchMail, $regDate)),
      "field" => new FieldModel(
        '',
        '',
        '',
        null,
        'select',
        true,
        $this->userDB->getUsers($user->getRole())
      ),
      "form" => new FormModel(
        'Search user',
        [
          new Field(
            new FieldModel(
              'Username',
              'searchMail',
              'searchMail',
              'example@gmail.com',
              'text',
              false
            )
          ),
          new Field(
            new FieldModel(
              'Name',
              'searchName',
              'searchName',
              'Francesco',
              'text',
              false
            )
            ),
          new Field(
            new FieldModel(
              'Registration date',
              'regDate',
              'regDate',
              'yyyy-mm-dd',
              'date',
              false
            )
          )
        ],
        'Search',
        false,
        null,
        '',
        null,
        'get'
      )
    ];
  }
}
