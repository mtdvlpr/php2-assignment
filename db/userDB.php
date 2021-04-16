<?php
require_once __DIR__ . '/baseDB.php';
require_once __DIR__ . '/../model/user.php';

class UserDB extends BaseDB
{
  private string $salt;

  public function __construct()
  {
    $this->salt = '$6$rounds=7000$fishandchips$';
  }

  /**
   * Get a selection of users from the database
   *
   * @return UserModel[] An array of users.
   */
  public function getUsers(
    int $role = 3,
    string $name = '',
    string $username = '',
    string $registrationDate = ''
  ): array
  {
    $query = 'SELECT id, `name`, username, `password`, is_active, `role`, profile_picture, registration_date
      FROM users
      WHERE `role` < ?
        AND `name` LIKE ?
        AND username LIKE ?
        AND registration_date LIKE ?
      ';

    $result = $this->executeQueryList(
      $query,
      'isss',
      [$role, "%$name%", "%$username%", "%$registrationDate%"]
    );

    $users = [];

    foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
      $users[] = new UserModel(
        $row['name'],
        $row['username'],
        $row['password'],
        $row['profile_picture'],
        $row['role'],
        $row['is_active'],
        $row['registration_date'],
        $row['id']
      );
    }

    return $users;
  }

  /**
   * Select a specific user based on it's username/email
   *
   * @param string $username The username/email of the user
   *
   * @return array The result of the query in the form of an associative array
   */
  public function getUser(string $searchUsername, string $hash = ''): userModel | null
  {
    $query = 'SELECT id, `name`, username, `password`, is_active, `role`, profile_picture, `hash`, registration_date
      FROM users
      WHERE username = ?
        AND `hash` LIKE ?';

    $this->executeQuery(
      $query,
      'ss',
      [$searchUsername, "%$hash%"],
      $id,
      $name,
      $username,
      $password,
      $isActive,
      $role,
      $profilePicture,
      $hash,
      $registrationDate
    );

    if ($id == null) {
      return null;
    }

    return new UserModel(
      $name,
      $username,
      $password,
      $profilePicture,
      $role,
      $isActive,
      $registrationDate,
      $id,
      $hash
    );
  }

  /**
   * Add a user to the database
   *
   * @param string $name The name of the user
   * @param string $username The username/email of the user
   * @param string $password The password of the user
   * @param string $hash A long string to make sure the user has access to the email given
   * @param int $isActive If the user is created by the admin, the account is immediately activated
   */
  public function addUser(string $name, string $username, string $password, string $hash = '', bool $isActive = true): void
  {
    $this->executeMutation('INSERT INTO users (`name`, username, `password`, registration_date, is_active, query_date, `hash`) VALUES (?, ?, ?, ?, ?, ?, ?)', 'sssssss', [$name, $username, $password, date("Y-m-d"), $isActive, date("Y-m-d"), $hash]);
  }

  /**
   * Updates a user in the database
   *
   * @param UserModel $user
   */
  public function updateUser(UserModel $user): void
  {
    $query = 'UPDATE users
    SET
      `name` = ?,
      username = ?,
      `password` = ?,
      is_active = ?,
      `role` = ?,
      profile_picture = ?,
      query_date = ?,
      `hash` = ?
    WHERE id = ?';

    $this->executeMutation(
      $query,
      'ssssisssi',
      [$user->getName(), $user->getUsername(), $user->getPassword(), $user->getIsActive(), $user->getRole(), $user->getProfilePicture(), date("Y-m-d"), $user->getHash(), $user->getId()]
    );
  }

  /**
   * Delete a user
   *
   * @param int $id The id of the user
   */
  public function deleteUser(int $id): void
  {
      $this->executeMutation('DELETE FROM users WHERE `id` = ?', 'i', [$id]);
  }

  /**
   * Get the value of salt
   */
  public function getSalt(): string
  {
    return $this->salt;
  }
}
