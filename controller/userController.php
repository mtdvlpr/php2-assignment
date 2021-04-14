<?php
require_once __DIR__ . '/../db/userDB.php';
require_once __DIR__ . '/../model/user.php';

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
                mail("$username", "Account Verification", "Dear $name,\n\nThank you for creating an account!\n\nClick this link to activate your account:\nhttp://www.643622.infhaarlem.nl/verify?email=$username&hash=$hash&new=none\n\nKind regards,\n\n\n643622.infhaarlem.nl", 'From:noreply@643622.infhaarlem.nl');

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
          $contentClass,
          'Already have an account? <a href="/login">Log in</a>.'
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
          else if (!$user->isActive()) {
            $content = 'This account has not been activated yet.';
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

  public function getForgotPage(): array
  {
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
          'A new password will be sent to your email address.',
          '',
          "Don't have an account yet? <a href='/signup'>Sign up</a>."
        )
      ]
    ];
  }
}
