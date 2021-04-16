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
            <th>Role</th>
            <th>Reg. date</th>
          </tr>
    ";

    // For each user, echo the table row with user data
    foreach ($this->table->getUsers() as $user) {
      $src = $user->getProfilePicture();
      $username = $user->getUsername();
      $name = $user->getName();
      $active = $user->getIsActive() ? 'Yes' : 'No';
      $registrationDate = date('d-m-Y', strtotime($user->getRegistrationDate()));
      $role = match($user->getRole()) {
        0 => 'User',
        1 => 'Admin',
        2 => 'Superadmin'
      };

      echo "
        <tr>
          <td><img src='$src' alt='Profile Picture' class='profile-pic'/></td>
          <td class='notranslate'>$username</td>
          <td class='notranslate'>$name</td>
          <td>$active</td>
          <td>$role</td>
          <td>$registrationDate</td>
        </tr>
      ";
    }

    echo /*html*/"</table>
        </section>
    ";
  }
}
