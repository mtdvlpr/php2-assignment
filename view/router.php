<?php
session_start();
require_once __DIR__ . '/templateEngine.php';

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

  public function __construct()
  {
    $this->activePath = $this->getPath();
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
    $templateEngine = new templateEngine(__DIR__ . '/templates');

    switch ($this->activePath) {
      case '/':
      case '/index.php':
        $isLoggedIn = isset($_SESSION["login"]);
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
            "styles" => ['form'],
            "scripts" => ['captcha'],
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
                'Send',
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
                'Search',
                false,
                null,
                null,
                'get'
              ),
              $this->movieController->getMovieArticle()
            ]
          ]
        );
        break;

      case (preg_match("/\/collection\/(.*)/i", $this->activePath, $matches) ? true : false):
        break;

      case '/signup':
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
