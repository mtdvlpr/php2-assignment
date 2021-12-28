<?php
require_once __DIR__ . '/../db/userDB.php';
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/../model/table.php';
require_once __DIR__ . '/../model/field.php';

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require_once __DIR__ . '/../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php';
require_once __DIR__ . '/../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Spreadsheet.php';
require_once __DIR__ . '/../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Helper/Sample.php';

class AdminController
{
  private UserDB $userDB;

  public function __construct()
  {
    $this->userDB = new UserDB();
  }

  // Get the /admin page
  public function getAdminPage(
    UserModel $user,
    string $searchMail = '',
    string $searchName = '',
    string $regDate = '',
    ?userModel $newUser = null,
    ?string $username = null,
    ?string $password = null,
    ?UserModel $updatedUser = null,
    ?string $confirmNewPass = null,
    ?array $fileArray = null
  ): array
  {
    // Standard values
    $updateFeedback = '<p>Empty fields will remain unchanged.</p>';
    $addFeedback = null;
    $addClass = ' class="success"';
    $removeFeedback = null;
    $removeClass = ' class="success"';
    $roleFeedback = 'Admins lose their rights, users get rights.';
    $roleClass = '';

    // Add new user
    if ($newUser != null) {
      try {
        $addFeedback = $this->addUser($user->getRole(), $newUser);
      } catch (Exception $e) {
        $addFeedback = $e->getMessage();
        $addClass = ' class="error"';
      }
    }

    // Update user
    else if ($updatedUser != null) {
      $updateFeedback = $this->updateUser($user, $updatedUser, $username, $password, $confirmNewPass, $fileArray);
    }

    else if ($username != null) {

      // Remove account
      if ($password != null) {
        try {
          $removeFeedback = $this->removeAccount($user, $username, $password);
        } catch (Exception $e) {
          $removeFeedback = $e->getMessage();
          $removeClass = ' class="error"';
        }
      }

      // Change role
      else {
        try {
          $roleFeedback = $this->changeRole($user->getRole(), $username);
          $roleClass = ' class="success"';
        } catch (Exception $e) {
          $roleFeedback = $e->getMessage();
          $roleClass = ' class="error"';
        }
      }
    }

    // Return the page data in an array
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
      ),
      "updateFeedback" => $updateFeedback,
      "removeFeedback" => $removeFeedback,
      "removeClass" => $removeClass,
      "addFeedback" => $addFeedback,
      "addClass" => $addClass,
      "roleFeedback" => $roleFeedback,
      "roleClass" => $roleClass
    ];
  }

  private function addUser(int $userRole, UserModel $newUser): string
  {
    $username = $newUser->getUsername();
    $name = $newUser->getName();
    $password = $newUser->getPassword();

    // Validate the user
    if ($userRole < 2) {
      throw new Exception("You don't have the rights to do this.");
    } else if (empty($username) || empty($name) || empty($password)) {
      throw new Exception("Please fill in all fields.");
    } else if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
      throw new Exception("$username is not a valid username.");
    } else if ($this->userDB->getUser($username) != null) {
      throw new Exception("This username is already taken.");
    }else if (preg_match('~[0-9]~', $name) === 1) {
      throw new Exception("$name is not a valid name.");
    } else if (strlen($password) < 8) {
      throw new Exception("The password should have a length of 8 or more.");
    } else {
      try {
        $this->userDB->addUser($name, $username, crypt($password, $this->userDB->getSalt()));
        return "The user has been created.";
      } catch (Exception $e) {
        throw new Exception('Something went wrong while adding the user: ' . $e->getMessage());
      }
    }
  }

  private function removeAccount(UserModel $user, string $username, string $adminPassword): string
  {
    // Validate the user
    if ($user->getRole() < 2) {
      throw new Exception("You don't have the rights to do this.");
    } else if (!$user->checkPassword(crypt($adminPassword, $this->userDB->getSalt()))) {
      throw new Exception("The admin password was wrong.");
    } else {
      $searchUser = $this->userDB->getUser($username);

      if ($searchUser == null) {
        throw new Exception("The user $username doesn't exist.");
      } else {
        try {
          $this->userDB->deleteUser($searchUser->getId());
          return "$username has been removed.";
        } catch (Exception $e) {
          throw new Exception("Something went wrong while removing the user: " . $e->getMessage());
        }
      }
    }
  }

  private function changeRole(int $userRole, string $username): string
  {
    if ($userRole < 2) {
      throw new Exception("You don't have the rights to do this.");
    } else {
      $user = $this->userDB->getUser($username);

      if ($user == null) {
        throw new Exception("$username doesn't exist.");
      } else if ($user->getRole() == 2) {
        throw new Exception("You can't remove superadmin rights.");
      } else {
        $user->setRole($user->getRole() == 0 ? 1 : 0);

        try {
          $this->userDB->updateUser($user);
          return "$username is now a " . ($user->getRole() == 0 ? "user." : "admin.");
        } catch (Exception $e) {
          throw new Exception("Something went wrong while changing the role: " . $e->getMessage());
        }
      }
    }
  }

  private function updateUser(
    UserModel $admin,
    UserModel $updatedUser,
    string $username,
    string $adminPassword,
    string $confirmNewPass,
    array $fileArray
  ): string
  {
    // Validate admin password and selected username
    $updateFeedback = '';
    $user = $this->userDB->getUser($username);
    if (empty($adminPassword) || empty($username)) {
      $updateFeedback = '<p class="warning"><i class="fa fa-warning"></i> Please enter your admin password and select a username.</p>';
    } else if (!$admin->checkPassword(crypt($adminPassword, $this->userDB->getSalt()))) {
      $updateFeedback = '<p class="error"><i class="fa fa-times-circle"></i> You entered the wrong admin password.</p>';
    } else if ($user == null) {
      $updateFeedback = "<p class='error'><i class='fa fa-times-circle'></i> The user $username could not be found.</p>";
    } else {
      $updateUser = false;

      // Validate new email address
      $newEmail = $updatedUser->getUsername();
      if (!empty($newEmail) && $newEmail != $user->getUsername()) {
        try {
          $updateFeedback .= '<p class="success"><i class="fa fa-check"></i> ' . $this->validateNewEmail($newEmail) . '</p>;';
          $updateUser = true;
          $user->setUsername($newEmail);
        } catch (Exception $e) {
          $updateFeedback .= '<p class="error"><i class="fa fa-times-circle"></i> ' . $e->getMessage() . '</p>;';
        }
      }

      // Validate new name
      $name  = $updatedUser->getName();
      if (!empty($name) && $name != $user->getName()) {
        try {
          $updateFeedback .= '<p class="success"><i class="fa fa-check"></i> ' . $this->validateNewName($name) . '</p>;';
          $updateUser = true;
          $user->setName($name);
        } catch (Exception $e) {
          $updateFeedback .= '<p class="error"><i class="fa fa-times-circle"></i> ' . $e->getMessage() . '</p>;';
        }
      }

      // Validate new password
      $pass = $updatedUser->getPassword();
      if (!empty($pass) && $pass != $user->getPassword()) {
        try {
          $updateFeedback .= '<p class="success"><i class="fa fa-check"></i> ' . $this->validatePassword($pass, $confirmNewPass) . '</p>;';
          $updateUser = true;
          $user->setPassword(crypt($pass, $this->userDB->getSalt()));
        } catch (Exception $e) {
          $updateFeedback .= '<p class="error"><i class="fa fa-times-circle"></i> ' . $e->getMessage() . '</p>;';
        }
      }

      // Validate new profile picture
      if (!empty($fileArray["pic"]["name"])) {
        try {
          $updateFeedback .= '<p class="success"><i class="fa fa-check"></i> ' . $this->validateProfilePicture($user, $fileArray) . '</p>;';
          $updateUser = true;
        } catch (Exception $e) {
          $updateFeedback .= '<p class="error"><i class="fa fa-times-circle"></i> ' . $e->getMessage() . '</p>;';
        }
      }

      // If one or more changes have been validated successfully, update user in database
      if ($updateUser) {
        try {
          $this->userDB->updateUser($user);
        } catch (Exception $error) {
          $msg = $error->getMessage();
          $updateFeedback = "<p class='error'><i class='fa fa-times-circle'></i> Something went wrong while updating the user: $msg</p>";
        }
      }
    }

    return $updateFeedback;
  }

  private function validateNewEmail(string $newEmail): string
  {
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
      throw new Exception("$newEmail is not a valid email address.");
    } else if ($this->userDB->getUser($newEmail) != null) {
      throw new Exception("A user already exists for $newEmail.");
    } else {
      return "The user's email address has been changed.";
    }
  }

  private function validateNewName(string $name): string
  {
    if (preg_match('~[0-9]~', $name) === 1) {
      throw new Exception("$name is not a valid name.");
    } else {
      return "The user's name has been changed successfully.";
    }
  }

  private function validatePassword(string $pass, string $confirm): string
  {
    if (strlen($pass) < 8) {
      throw new Exception('The new password should have a length of 8 or more.');
    } else if ($pass != $confirm) {
      throw new Exception('The confirmed new password does not match the new password.');
    } else {
      return "The user's password has been changed.";
    }
  }

  private function validateProfilePicture(userModel $user, array $fileArray): string
  {
    // Get image info
    $targetDir = "img/uploads/";
    $fileName = basename($fileArray["pic"]["name"]);
    $fileName = "user" . $user->getId() . "-" . basename($fileArray["pic"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

    // Validate image
    if (!in_array($fileType, $allowTypes)) {
      throw new Exception('Only JPG, JPEG, PNG & GIF files are allowed.');
    } else if (file_exists($targetFilePath)) {
      throw new Exception("$fileName already exists. Change the name and try again.");
    } else if (move_uploaded_file($fileArray["pic"]["tmp_name"], $targetFilePath)) {
      if ($user->getProfilePicture() !== "/img/fillerface.png") {
        unlink(__DIR__ . '/../public' . $user->getProfilePicture());
      }

      // Convert image to image object
      $img = '';
      switch ($fileType) {
        case 'jpg':
        case 'jpeg':
          $img = imagecreatefromjpeg($targetFilePath);
          break;

        case 'gif':
          $img = imagecreatefromgif($targetFilePath);
          break;

        default:
          $img = imagecreatefrompng($targetFilePath);
          break;
      }

      // Make picture black and white
      imagefilter($img, IMG_FILTER_GRAYSCALE);
      imagefilter($img, IMG_FILTER_CONTRAST, -100);

      imagepng($img, $targetFilePath);

      $user->setProfilePicture('/' . $targetFilePath);
      return "The user's profile picture has been updated.";
    } else {
      throw new Exception("There was an error while uploading the new profile picture.");
    }
  }

  public function exportUsers(UserModel $user, string $format, string $role, string $reg): void
  {
    $users = $this->userDB->getUsers($user->getRole());

    if ($format == 'excel') {
      $this->exportExcel($users, $role == 'true', $reg == 'true');
    } else {
      $this->exportCSV($users, $role == 'true', $reg == 'true');
    }

    exit;
  }

  private function exportCSV(array $users, bool $includeRole, bool $includeReg): void
  {
    $delimiter = ",";
    $filename = "users_" . date('Y-m-d') . ".csv";

    //create a file pointer
    $f = fopen('php://memory', 'w');

    //set column headers
    $fields = array('ID', 'Username', 'Name', 'Status');

    if ($includeRole) {
      array_push($fields, 'Role');
    }
    if ($includeReg) {
      array_push($fields, 'Registration date');
    }

    fputcsv($f, $fields, $delimiter);

    //output each row of the data, format line as csv and write to file pointer
    foreach ($users as $user) {
      $status = ($user->getIsActive()) ? 'Active' : 'Inactive';
      $registrationDate = date('d-m-Y', strtotime($user->getRegistrationDate()));
      $role = match ($user->getRole()) {
        0 => 'User',
        1 => 'Admin',
        2 => 'Superadmin'
      };
      $lineData = array($user->getId(), $user->getUsername(), $user->getName(), $status);

      if ($includeRole) {
        array_push($lineData, $role);
      }
      if ($includeReg) {
        array_push($lineData, $registrationDate);
      }

      fputcsv($f, $lineData, $delimiter);
    }

    //move back to beginning of file
    fseek($f, 0);

    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    //output all remaining data on a file pointer
    fpassthru($f);
  }

  private function exportExcel(array$users, bool $includeRole, bool $includeReg): void
  {
    // Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $title = 'users_' . date('Y-m-d');

    // Set document properties
    $spreadsheet->getProperties()
    ->setCreator('Movies For You')
    ->setLastModifiedBy('Movies For You')
    ->setTitle($title);

    // Add headers
    $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A1', 'ID')
      ->setCellValue('B1', 'Username')
      ->setCellValue('C1', 'Name')
      ->setCellValue('D1', 'Status');

      if ($includeRole) {
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', 'Role');
      }
      if ($includeReg) {
        $cell = $includeRole ? 'F1' : 'E1';
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($cell, 'Registration date');
      }

    $row = 2;
    foreach ($users as $user) {
      $status = ($user->getIsActive()) ? 'Active' : 'Inactive';
      $registrationDate = date('d-m-Y', strtotime($user->getRegistrationDate()));
      $role = match ($user->getRole()) {
        0 => 'User',
        1 => 'Admin',
        2 => 'Superadmin'
      };

      $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, $user->getId())
        ->setCellValue('B' . $row, $user->getUsername())
        ->setCellValue('C' . $row, $user->getName())
        ->setCellValue('D' . $row, $status);

      if ($includeRole) {
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $row, $role);
      }
      if ($includeReg) {
        $cell = $includeRole ? 'F' : 'E';
        $spreadsheet->setActiveSheetIndex(0)->setCellValue($cell . $row, $registrationDate);
      }

      $row++;
    }

    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Users');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'. $title .'.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
  }
}
