<?php
require_once __DIR__ . '/../db/userDB.php';
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/../model/mailer.php';

class UserController
{
  private UserDB $userDB;
  private string $salt;

  public function __construct()
  {
    $this->userDB = new UserDB();
    $this->salt = '$6$rounds=7000$fishandchips$';
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
    $contentClass = ' class="error"';

    // Validate signup request if given
    if ($username != null) {
      if (empty($username) || empty($password) || empty($name) || empty($confirm)) {
        $content = 'Please fill in all fields.';
        $contentClass = ' class="warning"';
      } else if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $content = "$username is not a valid email address.";
      } else if (preg_match('~[0-9]~', $name) === 1) {
        $content = "$name is not a valid name.";
      } else if (strlen($password) < 8) {
        $content = "Your password should have a length of 8 or more.";
      } else if ($password != $confirm) {
        $content = "The confirmed password does not match the password you entered.";
      }
      else {
        try {
          $user = $this->userDB->getUser($username);

          if ($user != null) {
            $content = "An account already exists for $username.";
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
                $this->userDB->addUser($name, $username, crypt($password, $this->salt), $hash, 0);

                // Send verification mail
                $mailer = new Mailer();
                $mailer->sendMail(
                  subject: "Account Verification",
                  body: "Dear $name,<br><br>Thank you for creating an account!<br><br><a href='http://www.643622.infhaarlem.nl/verify?email=$username&hash=$hash&new=none'>Verify your account</a><br><br>Kind regards,<br><br><br>The Movies For You team",
                  address: $username
                );

                // Show user feedback
                $content = "Your account was successfully created. Check your email to activate it.";
                $contentClass = ' class="success"';
              } catch (Exception $error) {
                $content = $error->getMessage();
              }
            } else {
              $content = 'Robot verification failed, please try again.';
            }
          } else {
            $content = "Please check the reCAPTCHA box.";
          }
        } catch (Exception $error) {
          $content = $error->getMessage();
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
      }
      else if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $content = "$username is not a valid email address.";
      } else {
        try {
          $user = $this->userDB->getUser($username);
          if ($user == null) {
            $content = "The user $username was not found.";
          }
          else if (!$user->getIsActive()) {
            $content = 'This account has not been activated yet. Check your email to active it.';
            $contentClass = ' class="warning"';
          } else if (!$user->checkPassword(crypt($password, $this->salt))) {
            $content = 'Your password is incorrect.';
          } else {
            $_SESSION['login'] = serialize($user);
            header('Location: /');
          }
        } catch (Exception $error) {
          $content = $error->getMessage();
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
    $contentClass = ' class="error"';

    // Validate login attempt if there is one
    if ($email != null) {
      if (empty($email) || empty($confirm)) {
        $content = 'Please fill in both fields.';
        $contentClass = ' class="warning"';
      } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $content = "$email is not a valid email address.";
      } else if ($email != $confirm) {
        $content = "The confirmed email doesn't match the email you entered.";
      } else {
        try {
          $user = $this->userDB->getUser($email);
          if ($user == null) {
            $content = "The user $email was not found.";
          } else if (!$user->getIsActive()) {
            $content = 'This account has not been activated yet. Check your email to active it.';
            $contentClass = ' class="warning"';
          } else {

            // Generate new password
            $newPassword = "";
            $characters = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ1234567890";

            for ($i = 0; $i < 8; $i++) {
              $position = rand(0, 61);
              $newPassword .= substr($characters, $position, 1);
            }

            // Update the database
            $user->setPassword(crypt($newPassword, $this->salt));
            $this->userDB->updateUser($user);

            // Send email
            $mailer = new Mailer();
            $mailer->sendMail(
              subject: "New Password",
              body: "Dear user,<br><br>You have forgotten your password, so we made a new one for you!<br><br>Your new password is: $newPassword<br><br>Kind regards,<br><br><br>The Movies For You team",
              address: $email
            );

            // Give user feedback
            $content = "A new password has been sent to your email address.";
            $contentClass = ' class="success"';
          }
        } catch (Exception $error) {
          $content = $error->getMessage();
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
    $updateFeedback = '<p>Empty fields will remain unchanged.</p>';
    $pictureFeedback = null;
    $pictureClass = ' class="success"';
    $removeFeedback = null;
    $removeClass = ' class="error"';

    // Remove picture
    if ($removePicture) {
      if ($user->getProfilePicture() !== "/img/fillerface.png") {
        // Remove old picture
        unlink(__DIR__ . '/../public' . $user->getProfilePicture());

        // Set new picture and update database and session
        $user->setProfilePicture('/img/fillerface.png');
        $this->userDB->updateUser($user);
        $_SESSION["login"] = serialize($user);
      }
      $pictureFeedback = 'Your profile picture has been removed.';
    }

    // Remove account
    else if ($confirmEmail != null) {
      if ($confirmEmail != $user->getUsername()) {
        $removeFeedback = "You entered the wrong email address.";
      } else if ($user->checkPassword(crypt($confirmPassword, $this->salt))) {
          $this->userDB->deleteUser($user->getUsername());
          unset($_SESSION["login"]);
          header('Location: /');
      } else {
        $removeFeedback = "You entered the wrong password.";
      }
    }

    // Update account
    else if ($updatedUser != null) {
      if (empty($confirmPassword)) {
        $updateFeedback = '<p class="warning"><i class="fa fa-warning"></i> Please enter your current password.</p>';
      } else if (!$user->checkPassword(crypt($confirmPassword, $this->salt))) {
        $updateFeedback = '<p class="error"><i class="fa fa-times-circle"></i> You entered the wrong current password.</p>';
      } else {
        $updateUser = false;
        $updateFeedback = '';

        // Validate new email address
        $newEmail = $updatedUser->getUsername();
        if (!empty($newEmail) && $newEmail != $user->getUsername()) {
          if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $updateFeedback .= '<p class="error"><i class="fa fa-times-circle"></i> You entered an invalid email address.</p>;';
          } else if ($this->userDB->getUser($newEmail) != null) {
            $updateFeedback .= "<p class='error'><i class='fa fa-times-circle'></i> A user with given email already exists.</p>;";
          } else {
            $oldUsername = $user->getUsername();
            $hash = md5(rand(0, 1000));
            $user->setHash($hash);

            try {
              // Send notification mail to old address
              $mailer = new Mailer();
              $mailer->sendMail(
                subject: "Email Address Changed",
                body: "Dear user,<br><br>The email address linked to your account has been changed to: $newEmail.<br><br>If this was not you, please contact us: http://www.643622.infhaarlem.nl/contact<br><br>Kind regards,<br><br><br>The Movies For You team",
                address: $oldUsername
              );

              // Send verification mail to new address
              $mailer = new Mailer();
              $mailer->sendMail(
                subject: "New Email Verification",
                body: "Dear user,<br><br>You want to change the email address linked to your account.<br><br>Click this link to activate your new email:<br>http://www.643622.infhaarlem.nl/verify?email=$oldUsername&hash=$hash&new=$newEmail<br><br>Kind regards,<br><br><br>The Movies For You team",
                address: $newEmail
              );

              $updateFeedback .= '<p class="success"><i class="fa fa-check"></i> An email has been sent to your new email address for verification.</p>;';
            } catch (Exception $error) {
              $msg = $error->getMessage();
              $updateFeedback .= "<p class='error'><i class='fa fa-times-circle'></i> Something went wrong while sending an email: $msg</p>;";
            }
          }
        }

        // Validate new name
        $name = $updatedUser->getName();
        if (!empty($name) && $name != $user->getName()) {
          if (preg_match('~[0-9]~', $name) === 1) {
            $updateFeedback .= "<p class='error'><i class='fa fa-times-circle'></i> $name is not a valid name.</p>;";
          } else {
            $updateUser = true;
            $user->setName($name);
            $updateFeedback .= '<p class="success"><i class="fa fa-check"></i> Your name has been changed successfully.</p>;';
          }
        }

        // Validate new password
        $pass = $updatedUser->getPassword();
        if (!empty($pass) && $pass != $user->getPassword()) {
          if (strlen($pass) < 8) {
            $updateFeedback .= '<p class="error"><i class="fa fa-times-circle"></i> Your new password should have a length of 8 or more.</p>;';
          } else if ($pass != $confirmNewPass) {
            $updateFeedback .= '<p class="error"><i class="fa fa-times-circle"></i> The confirmed new password does not match the new password.</p>;';
          } else {
            $updateUser = true;
            $user->setPassword(crypt($pass, $this->salt));
            $updateFeedback .= '<p class="success"><i class="fa fa-check"></i> Your password has been changed.</p>;';
          }
        }

        // Validate new profile picture
        if (!empty($fileArray["pic"]["name"])) {
          $targetDir = "img/uploads/";
          $fileName = basename($fileArray["pic"]["name"]);
          $targetFilePath = $targetDir . $fileName;
          $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
          $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

          if (!in_array($fileType, $allowTypes)) {
            $updateFeedback .= '<p class="error"><i class="fa fa-times-circle"></i> Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>;';
          } else if (file_exists($targetFilePath)) {
            $updateFeedback .= "<p class='error'><i class='fa fa-times-circle'></i> $fileName already exists. Change the name and try again.</p>;";
          } else if (move_uploaded_file($fileArray["pic"]["tmp_name"], $targetFilePath)) {
            if ($user->getProfilePicture() !== "/img/fillerface.png") {
              unlink(__DIR__ . '/../public' . $user->getProfilePicture());
            }

            $updateUser = true;
            $user->setProfilePicture('/' . $targetFilePath);
            $updateFeedback .= '<p class="success"><i class="fa fa-check"></i> Your profile picture has been updated.</p>;';
          } else {
            $updateFeedback .= "<p class='error'><i class='fa fa-times-circle'></i> There was an error while uploading your new profile picture.</p>;";
          }
        }

        // If one or more changes have been validated successfully, update user
        if ($updateUser) {
          try {
            $this->userDB->updateUser($user);
            $_SESSION['login'] = serialize($user);
          } catch (Exception $error) {
            $updateFeedback = '<p class="error"><i class="fa fa-times-circle"></i> Something went wrong while updating your account.</p>';
          }
        }
      }
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
}
