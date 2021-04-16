<?php
require_once __DIR__ . '/../db/userDB.php';
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/../model/mailer.php';

class UserController
{
  private UserDB $userDB;

  public function __construct()
  {
    $this->userDB = new UserDB();
  }

  public function getSignUpPage(
    ?string $username = null,
    ?string $name = null,
    ?string $password = null,
    ?string $confirm = null,
    ?string $captcha = null
  ): array
  {
    $content = null;
    $contentClass = ' class="success"';

    // Validate signup request if given
    if ($username != null) {
      if (empty($username) || empty($password) || empty($name) || empty($confirm)) {
        $content = 'Please fill in all fields.';
        $contentClass = ' class="warning"';
      } else {
        try {
          $content = $this->validateSignUp($username, $name, $password, $confirm, $captcha);
        } catch (Exception $e) {
          $content = $e->getMessage();
          $contentClass = ' class="error"';
        }
      }
    }

    // Return data needed to fill the template
    return [
      "title" => "Sign up",
      "user" => null,
      "asideArticles" => [ArticleModel::get('about')],
      "mainArticles" => [
        new FormModel(
          'Sign up',
          [
            new Field(
              new FieldModel(
                'Username',
                'email',
                'username',
                'example@gmail.com',
                'email'
              )
            ),
            new Field(
              new FieldModel(
                'Name',
                'name',
                'name',
                'Francesco de Bernardo'
              )
            ),
            new Field(
              new FieldModel(
                'Password',
                'pass',
                'password',
                'SecretPassword123!',
                'password'
              )
            ),
            new Field(
              new FieldModel(
                'Confirm password',
                'confirmpass',
                'confirm',
                'SecretPassword123!',
                'password'
              )
            )
          ],
          'Sign up',
          true,
          $content,
          $contentClass
        )
      ]
    ];
  }

  public function getLoginPage(?string $username = null, ?string $password = null): array
  {
    $content = null;
    $contentClass = ' class="error"';

    // Validate login attempt if there is one
    if ($username != null) {
      if (empty($username) || empty($password)) {
        $content = 'Please fill in a username and a password.';
        $contentClass = ' class="warning"';
      } else {
        try {
          $this->validateLogin($username, $password);
        } catch (Exception $e) {
          $content = $e->getMessage();
          $contentClass = ' class="error"';
        }
      }
    }

    // Return the necessary data for the view class
    return [
      "title" => "Log in",
      "user" => null,
      "asideArticles" => [ArticleModel::get('about')],
      "mainArticles" => [
        new FormModel(
          'Log in',
          [
            new Field(
              new FieldModel(
                'Username',
                'email',
                'username',
                'example@gmail.com',
                'email'
              )
            ),
            new Field(
              new FieldModel(
                'Password',
                'pass',
                'password',
                'SecretPassword123!',
                'password'
              )
            )
          ],
          'Log in',
          false,
          $content,
          $contentClass,
          "Don't have an account yet? <a href='/signup'>Sign up</a>.;Forgot password? <a href='/forgot'>Request new password</a>."
        )
      ]
    ];
  }

  public function getForgotPage(?string $email = null, ?string $confirm = null): array
  {
    $content = null;
    $contentClass = ' class="success"';

    // Validate login attempt if there is one
    if ($email != null) {
      if (empty($email) || empty($confirm)) {
        $content = 'Please fill in both fields.';
        $contentClass = ' class="warning"';
      } else {
        try {
          $content = $this->validateForgotRequest($email, $confirm);
        } catch (Exception $e) {
          $content = $e->getMessage();
          $contentClass = ' class="error"';
        }
      }
    }

    return [
      "title" => "Forgot password?",
      "user" => null,
      "asideArticles" => [ArticleModel::get('about')],
      "mainArticles" => [
        new FormModel(
          'Forgot password?',
          [
            new Field(
              new FieldModel(
                'Email Address',
                'email',
                'email',
                'example@gmail.com',
                'email'
              )
            ),
            new Field(
              new FieldModel(
                'Confirm email',
                'confirmemail',
                'confirm',
                'example@gmail.com',
                'email'
              )
            )
          ],
          'Send email',
          false,
          $content,
          $contentClass,
          "Don't have an account yet? <a href='/signup'>Sign up</a>."
        )
      ]
    ];
  }

  public function getVerifyPage(
    ?userModel $user,
    string $email,
    string $hash,
    string $new,
    bool $submitted
  ): array
  {
    unset($_SESSION['login']);

    if (!empty($email) && !empty($hash) && !empty($new)) {
      if ($submitted) {
        $searchUser = $this->userDB->getUser($email, $hash);
        if ($searchUser != null) {
          if ($new == "none") {
            $searchUser->setIsActive(true);
            $searchUser->setHash('');
            $content = 'Your account has been activated, you can now log in.';
          } else if (filter_var($new, FILTER_VALIDATE_EMAIL) && $this->userDB->getUser($new) == null) {
            $searchUser->setUsername($new);
            $content = 'Your email address has been changed. Log in using the new email.';
          } else {
            $content = 'Your new email address is invalid.';
          }
          try {
            $this->userDB->updateUser($searchUser);
          } catch (Exception $e) {
            $content = 'Something went wrong while updating your account: ' . $e->getMessage();
          }
        } else {
          $content = "We couldn't find your account, <a href='/signup'>try registering</a>.";
        }
      } else {
        $content = '<form method="post"><input type="submit" name="verify" class="linkButton" value="Activate your account/email."></form>';
      }
    } else {
      $content = "Something went wrong. Please go back to home.";
    }

    return [
      "title" => "Verify Page",
      "user" => null,
      "asideArticles" => [],
      "mainArticles" => [],
      "verifyContent" => $content
    ];
  }

  public function getAccountsPage(
    userModel $user,
    bool $removePicture = false,
    ?string $confirmEmail = null,
    ?string $confirmPassword = null,
    ?UserModel $updatedUser = null,
    ?string $confirmNewPass = null,
    ?array $fileArray = null
  ): array
  {
    // Standard values
    $updateFeedback = '<p>Empty fields will remain unchanged.</p>';
    $pictureFeedback = null;
    $pictureClass = ' class="success"';
    $removeFeedback = null;
    $removeClass = ' class="error"';

    // Remove picture
    if ($removePicture) {
      $pictureFeedback = $this->removePicture($user);
    }

    // Remove account
    else if ($confirmEmail != null) {
      $removeFeedback = $this->removeAccount($user, $confirmEmail, $confirmPassword);
    }

    // Update account
    else if ($updatedUser != null) {
      $updateFeedback = $this->updateAccount($user, $updatedUser, $confirmPassword, $confirmNewPass, $fileArray);
    }

    return [
      "title" => "Your Account",
      "user" => $user,
      "updateFeedback" => $updateFeedback,
      "pictureFeedback" => $pictureFeedback,
      "pictureClass" => $pictureClass,
      "removeFeedback" => $removeFeedback,
      "removeClass" => $removeClass
    ];
  }

  private function validateSignUp(string $username, string $name, string $password, string $confirm, string $captcha): string
  {
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("$username is not a valid email address.");
    } else if (preg_match('~[0-9]~', $name) === 1) {
      throw new Exception("$name is not a valid name.");
    } else if (strlen($password) < 8) {
      throw new Exception("Your password should have a length of 8 or more.");
    } else if ($password != $confirm) {
      throw new Exception("The confirmed password does not match the password you entered.");
    }
    else {
      try {
        $user = $this->userDB->getUser($username);

        if ($user != null) {
          throw new Exception("An account already exists for $username.");
        } else if (isset($captcha) && !empty($captcha)) {

          // Google secret API
          $secretAPIkey = '6Lenh-MZAAAAAMv--kR6my39trkTaJIxR34ujQnI';

          // reCAPTCHA response verification
          $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretAPIkey . '&response=' . $captcha);

          // Decode JSON data
          $response = json_decode($verifyResponse);
          if ($response->success) {
            try {
              $hash = md5(rand(0, 1000));

              // Add user to database
              $this->userDB->addUser($name, $username, crypt($password, $this->userDB->getSalt()), $hash, false);

              // Send verification mail
              $mailer = new Mailer();
              $mailer->sendMail(
                subject: "Account Verification",
                body: "Dear $name,<br><br>Thank you for creating an account!<br><br><a href='http://localhost:3000/verify?email=$username&hash=$hash&new=none'>Verify your account</a><br><br>Kind regards,<br><br><br>The Movies For You team",
                address: $username
              );

              // Show user feedback
              return "Your account was successfully created. Check your email to activate it.";
            } catch (Exception $error) {
              throw new Exception($error->getMessage());
            }
          } else {
            throw new Exception('Robot verification failed, please try again.');
          }
        } else {
          throw new Exception("Please check the reCAPTCHA box.");
        }
      } catch (Exception $error) {
        throw new Exception($error->getMessage());
      }
    }
  }

  private function validateLogin(string $username, string $password): void
  {
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("$username is not a valid email address.");
    } else {
      try {
        $user = $this->userDB->getUser($username);
        if ($user == null) {
          throw new Exception("The user $username was not found.");
        }
        else if (!$user->getIsActive()) {
          throw new Exception('This account has not been activated yet. Check your email to active it.');
        } else if (!$user->checkPassword(crypt($password, $this->userDB->getSalt()))) {
          throw new Exception('Your password is incorrect.');
        } else {
          $_SESSION['login'] = serialize($user);
          header('Location: /');
        }
      } catch (Exception $error) {
        throw new Exception($error->getMessage());
      }
    }
  }

  private function validateForgotRequest(string $email, string $confirm): string
  {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception("$email is not a valid email address.");
    } else if ($email != $confirm) {
      throw new Exception("The confirmed email doesn't match the email you entered.");
    } else {
      try {
        $user = $this->userDB->getUser($email);
        if ($user == null) {
          throw new Exception("The user $email was not found.");
        } else if (!$user->getIsActive()) {
          throw new Exception('This account has not been activated yet. Check your email to active it.');
        } else {

          // Generate new password
          $newPassword = "";
          $characters = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ1234567890";

          for ($i = 0; $i < 8; $i++) {
            $position = rand(0, 61);
            $newPassword .= substr($characters, $position, 1);
          }

          // Update the database
          $user->setPassword(crypt($newPassword, $this->userDB->getSalt()));
          $this->userDB->updateUser($user);

          // Send email
          $mailer = new Mailer();
          $mailer->sendMail(
            subject: "New Password",
            body: "Dear user,<br><br>You have forgotten your password, so we made a new one for you!<br><br>Your new password is: $newPassword<br><br>Kind regards,<br><br><br>The Movies For You team",
            address: $email
          );

          // Give user feedback
          return "A new password has been sent to your email address.";
        }
      } catch (Exception $error) {
        throw new Exception($error->getMessage());
      }
    }
  }

  private function removePicture(userModel $user): string
  {
    if ($user->getProfilePicture() !== "/img/fillerface.png") {
      // Remove old picture
      unlink(__DIR__ . '/../public' . $user->getProfilePicture());

      // Set new picture and update database and session
      $user->setProfilePicture('/img/fillerface.png');
      $this->userDB->updateUser($user);
      $_SESSION["login"] = serialize($user);
    }
    return 'Your profile picture has been removed.';
  }

  private function removeAccount(userModel $user, string $confirmEmail, string $confirmPassword): string
  {
    if ($confirmEmail != $user->getUsername()) {
      return "You entered the wrong email address.";
    } else if ($user->checkPassword(crypt($confirmPassword, $this->userDB->getSalt()))) {
      $this->userDB->deleteUser($user->getId());
      unset($_SESSION["login"]);
      header('Location: /');
    } else {
      return "You entered the wrong password.";
    }
  }

  private function updateAccount(userModel $user, userModel $updatedUser, string $confirmPassword, string $confirmNewPass, array $fileArray): string
  {
    // Validate current password
    $updateFeedback = '';
    if (empty($confirmPassword)) {
      $updateFeedback = '<p class="warning"><i class="fa fa-warning"></i> Please enter your current password.</p>';
    } else if (!$user->checkPassword(crypt($confirmPassword, $this->userDB->getSalt()))) {
      $updateFeedback = '<p class="error"><i class="fa fa-times-circle"></i> You entered the wrong current password.</p>';
    } else {
      $updateUser = false;

      // Validate new email address
      $newEmail = $updatedUser->getUsername();
      if (!empty($newEmail) && $newEmail != $user->getUsername()) {
        $updateFeedback .= $this->validateNewEmail($user, $newEmail);
      }

      // Validate new name
      $name  = $updatedUser->getName();
      if (!empty($name) && $name != $user->getName()) {
        try {
          $updateFeedback .= $this->validateNewName($user, $name);
          $updateUser = true;
        } catch (Exception $e) {
          $updateFeedback .= $e->getMessage();
        }
      }

      // Validate new password
      $pass = $updatedUser->getPassword();
      if (!empty($pass) && $pass != $user->getPassword()) {
        try {
          $updateFeedback .= $this->validatePassword($user, $pass, $confirmNewPass);
          $updateUser = true;
        } catch (Exception $e) {
          $updateFeedback .= $e->getMessage();
        }
      }

      // Validate new profile picture
      if (!empty($fileArray["pic"]["name"])) {
        try {
          $updateFeedback .= $this->validateProfilePicture($user, $fileArray);
          $updateUser = true;
        } catch (Exception $e) {
          $updateFeedback .= $e->getMessage();
        }
      }

      // If one or more changes have been validated successfully, update user in database
      if ($updateUser) {
        try {
          $this->userDB->updateUser($user);
          $_SESSION['login'] = serialize($user);
        } catch (Exception $error) {
          $msg = $error->getMessage();
          $updateFeedback = "<p class='error'><i class='fa fa-times-circle'></i> Something went wrong while updating your account: $msg</p>";
        }
      }
    }

    return $updateFeedback;
  }

  private function validateNewEmail(userModel $user, string $newEmail): string
  {
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
      return "<p class='error'><i class='fa fa-times-circle'></i> $newEmail is not a valid email address.</p>;";
    } else if ($this->userDB->getUser($newEmail) != null) {
      return "<p class='error'><i class='fa fa-times-circle'></i> A user already exists for $newEmail.</p>;";
    } else {
      $oldUsername = $user->getUsername();
      $hash = md5(rand(0, 1000));
      $user->setHash($hash);

      try {
        // Send notification mail to old address
        $mailer = new Mailer();
        $mailer->sendMail(
          subject: "Email Address Changed",
          body: "Dear user,<br><br>The email address linked to your account has been changed to: $newEmail.<br><br>If this was not you, please contact us: http://localhost:3000/contact<br><br>Kind regards,<br><br><br>The Movies For You team",
          address: $oldUsername
        );

        // Send verification mail to new address
        $mailer->sendMail(
          subject: "New Email Verification",
          body: "Dear user,<br><br>You want to change the email address linked to your account.<br><br>Click this link to activate your new email:<br>http://localhost:3000/verify?email=$oldUsername&hash=$hash&new=$newEmail<br><br>Kind regards,<br><br><br>The Movies For You team",
          address: $newEmail
        );

        return '<p class="success"><i class="fa fa-check"></i> An email has been sent to your new email address for verification.</p>;';
      } catch (Exception $error) {
        $msg = $error->getMessage();
        return "<p class='error'><i class='fa fa-times-circle'></i> Something went wrong while sending an email: $msg</p>;";
      }
    }
  }

  private function validateNewName(userModel $user, string $name): string
  {
    if (preg_match('~[0-9]~', $name) === 1) {
      throw new Exception("<p class='error'><i class='fa fa-times-circle'></i> $name is not a valid name.</p>;");
    } else {
      $user->setName($name);
      return '<p class="success"><i class="fa fa-check"></i> Your name has been changed successfully.</p>;';
    }
  }

  private function validatePassword(userModel $user, string $pass, string $confirmNewPass): string
  {
    if (strlen($pass) < 8) {
      throw new Exception('<p class="error"><i class="fa fa-times-circle"></i> Your new password should have a length of 8 or more.</p>;');
    } else if ($pass != $confirmNewPass) {
      throw new Exception('<p class="error"><i class="fa fa-times-circle"></i> The confirmed new password does not match the new password.</p>;');
    } else {
      $user->setPassword(crypt($pass, $this->userDB->getSalt()));
      return '<p class="success"><i class="fa fa-check"></i> Your password has been changed.</p>;';
    }
  }

  private function validateProfilePicture(userModel $user, array $fileArray): string
  {
    $targetDir = "img/uploads/";
    $fileName = basename($fileArray["pic"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

    if (!in_array($fileType, $allowTypes)) {
      throw new Exception('<p class="error"><i class="fa fa-times-circle"></i> Only JPG, JPEG, PNG & GIF files are allowed.</p>;');
    } else if (file_exists($targetFilePath)) {
      throw new Exception("<p class='error'><i class='fa fa-times-circle'></i> $fileName already exists. Change the name and try again.</p>;");
    } else if (move_uploaded_file($fileArray["pic"]["tmp_name"], $targetFilePath)) {
      if ($user->getProfilePicture() !== "/img/fillerface.png") {
        unlink(__DIR__ . '/../public' . $user->getProfilePicture());
      }

      $user->setProfilePicture('/' . $targetFilePath);
      return '<p class="success"><i class="fa fa-check"></i> Your profile picture has been updated.</p>;';
    } else {
      throw new Exception("<p class='error'><i class='fa fa-times-circle'></i> There was an error while uploading your new profile picture.</p>;");
    }
  }
}
