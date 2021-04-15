<?php
require_once __DIR__ . '/../../model/table.php';

/**
 * The view component for a table on the website.
 */
class Table
{
  public function __construct(private TableModel $table)
  {
  }

  public function render(): void
  {
    echo /*html*/"
      <section style='overflow-x: auto;'>
        <table>
          <tr>
            <th>Image</th>
            <th>Username</th>
            <th>Name</th>
            <th>Active</th>
            <th><abbr title='0 = user, 1 = admin, 2 = superadmin'>Role</abbr></th>
          </tr>
    ";

    foreach ($this->table->getUsers() as $user) {
      $src = $user->getProfilePicture();
      $username = $user->getUsername();
      $name = $user->getName();
      $active = $user->getIsActive();
      $role = $user->getRole();

      echo "
        <tr>
          <td><img src='$src' alt='Profile Picture' class='profile-pic'/></td>
          <td class='notranslate'>$username</td>
          <td class='notranslate'>$name</td>
          <td>$active</td>
          <td>$role</td>
        </tr>
      ";
    }

    echo /*html*/"</table>
        </section>
    ";
  }
}
