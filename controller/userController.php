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

  public function getSignUpPage(): array
  {
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
          null,
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

          if (!$user->isActive()) {
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
          "Don't have an account yet? <a href='/signup'>Sign up</a>."
        )
      ]
    ];
  }
}
