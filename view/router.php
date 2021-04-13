<?php
session_start();
require_once __DIR__ . '/templateEngine.php';

// Controllers
require_once __DIR__ . '/../controller/movieController.php';
require_once __DIR__ . '/../controller/userController.php';

// Models
require_once __DIR__ . '/../model/article.php';
require_once __DIR__ . '/../model/form.php';
require_once __DIR__ . '/../model/field.php';

// Views
require_once __DIR__ . '/components/field.php';

/**
 * The router, this is responsible for showing the user the correct View.
 */
class router
{
  private string $activePath;
  private MovieController $movieController;
  private UserController $userController;

  public function __construct()
  {
    $this->activePath = $this->getPath();
    $this->movieController = new MovieController();
    $this->userController = new UserController();
  }

  /**
   * @return string Get the path of the browser (ex: /settings)
   */
  private function getPath(): string
  {
    // Get the current Request URI and remove rewrite base path from it (= allows one to run the router in a sub folder)
    $uri = substr(rawurldecode($_SERVER['REQUEST_URI']), strlen("/"));

    // Don't take query params into account on the URL
    if (strstr($uri, '?')) {
      $uri = substr($uri, 0, strpos($uri, '?'));
    }

    // Remove trailing slash + enforce a slash at the start
    return '/' . trim($uri, '/');
  }

  /**
   * Render the correct view to the user
   */
  public function handleRoute()
  {
    $isLoggedIn = isset($_SESSION["login"]);
    $templateEngine = new templateEngine(__DIR__ . '/templates');

    switch ($this->activePath) {
      case '/':
      case '/index.php':
        echo $templateEngine->render(
          'main.php',
          [
            "title" => "Home",
            "asideArticles" => [
              ArticleModel::get('about'),
              ArticleModel::get('contact')
            ],
            "mainArticles" => [
              new ArticleModel(
                'Welcome!',
                "How great that you're visiting our website! We want you to be able to enjoy the rich culture of the movie industry.",
                $isLoggedIn ? null : '<a href="/register">Create an account</a> to get a more complete experience. With an account you can do, see and interact more!'
              ),
              ArticleModel::get('collection')
            ]
          ]
        );
        break;

      case '/about':
        echo $templateEngine->render(
          'main.php',
          [
            "title" => "About Us",
            "asideArticles" => [
              ArticleModel::get('collection')
            ],
            "mainArticles" => [
              new ArticleModel(
                'Who Are We?',
                "We are a company dedicated to providing everyone with a variety of amazing movies!"
              )
            ]
          ]
        );
        break;

      case '/contact':
        echo $templateEngine->render(
          'main.php',
          [
            "title" => "Home",
            "captcha" => true,
            "asideArticles" => [
              ArticleModel::get('about'),
              ArticleModel::get('collection')
            ],
            "mainArticles" => [
              new FormModel(
                'Contact Us',
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
                      'Name',
                      'name',
                      'name',
                      'Francisco de Bernardo'
                    )
                  ),
                  new Field(
                    new FieldModel(
                      'Subject',
                      'subject',
                      'subject',
                      'Homepage layout'
                    )
                  ),
                  new Field(
                    new FieldModel(
                      'Message',
                      'msg',
                      'msg',
                      'I love it!!!',
                      'textarea'
                    )
                  )
                ],
                'send',
                true
              )
            ]
          ]
        );
        break;

      case '/collection':
        echo $templateEngine->render(
          'main.php',
          [
            "title" => "Home",
            "asideArticles" => [
              ArticleModel::get('about'),
              ArticleModel::get('contact')
            ],
            "mainArticles" => [
              new FormModel(
                'Our Collection',
                [
                  new Field(
                    new FieldModel(
                      'Search by title',
                      'title',
                      'title',
                      'Star Wars',
                      'text',
                      false
                    )
                  ),
                  new Field(
                    new FieldModel(
                      'Order by',
                      'title;score',
                      'orderby',
                      null,
                      'radio',
                      false
                    )
                  )
                ],
                'search',
                false,
                null,
                null,
                'get'
              ),
              $this->movieController->getCollection($_GET['title'] ?? '', $_GET['orderby'] ?? 'id')
            ]
          ]
        );
        break;

      //TODO: make movie page
      case (preg_match("/\/collection\/(.*)/i", $this->activePath, $matches) ? true : false):
        break;

      case '/signup':
        echo $templateEngine->render(
          'main.php',
          [
            "title" => "Sign up",
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
          ]
        );
        break;

      //TODO: Make verify page
      case '/verify':
        echo $templateEngine->render(
          'main.php',
          [
            "title" => "Home",
            "asideArticles" => [
              ArticleModel::get('about'),
              ArticleModel::get('contact')
            ],
            "mainArticles" => [
              new ArticleModel(
                'Welcome!',
                "How great that you're visiting our website! We want you to be able to enjoy the rich culture of the movie industry.",
                $isLoggedIn ? null : '<a href="/register">Create an account</a> to get a more complete experience. With an account you can do, see and interact more!'
              ),
              ArticleModel::get('collection')
            ]
          ]
        );
        break;

      case '/login':
        echo $templateEngine->render(
          'main.php',
          [
            "title" => "Log in",
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
                null,
                "Don't have an account yet? <a href='/signup'>Sign up</a>.;Forgot password? <a href='/forgot'>Request new password</a>."
              )
            ]
          ]
        );
        break;

      //TODO: Make logout functionality
      case '/logout':
        echo $templateEngine->render(
          'main.php',
          [
            "title" => "Home",
            "asideArticles" => [
              ArticleModel::get('about'),
              ArticleModel::get('contact')
            ],
            "mainArticles" => [
              new ArticleModel(
                'Welcome!',
                "How great that you're visiting our website! We want you to be able to enjoy the rich culture of the movie industry.",
                $isLoggedIn ? null : '<a href="/register">Create an account</a> to get a more complete experience. With an account you can do, see and interact more!'
              ),
              ArticleModel::get('collection')
            ]
          ]
        );
        break;

      case '/forgot':
        echo $templateEngine->render(
          'main.php',
          [
            "title" => "Forgot password?",
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
          ]
        );
        break;

      //TODO: Make account page
      case '/account':
        echo $templateEngine->render(
          'main.php',
          [
            "title" => "Home",
            "asideArticles" => [
              ArticleModel::get('about'),
              ArticleModel::get('contact')
            ],
            "mainArticles" => [
              new ArticleModel(
                'Welcome!',
                "How great that you're visiting our website! We want you to be able to enjoy the rich culture of the movie industry.",
                $isLoggedIn ? null : '<a href="/register">Create an account</a> to get a more complete experience. With an account you can do, see and interact more!'
              ),
              ArticleModel::get('collection')
            ]
          ]
        );
        break;

      //TODO: Make admin page
      case '/admin':
        echo $templateEngine->render(
          'main.php',
          [
            "title" => "Home",
            "asideArticles" => [
              ArticleModel::get('about'),
              ArticleModel::get('contact')
            ],
            "mainArticles" => [
              new ArticleModel(
                'Welcome!',
                "How great that you're visiting our website! We want you to be able to enjoy the rich culture of the movie industry.",
                $isLoggedIn ? null : '<a href="/register">Create an account</a> to get a more complete experience. With an account you can do, see and interact more!'
              ),
              ArticleModel::get('collection')
            ]
          ]
        );
        break;

      default:
        require_once __DIR__ . "/404.php";
    }
  }
}
