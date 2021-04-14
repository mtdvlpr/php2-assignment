<?php
session_start();
require_once __DIR__ . '/templateEngine.php';

// Controllers
require_once __DIR__ . '/../controller/movieController.php';
require_once __DIR__ . '/../controller/userController.php';
require_once __DIR__ . '/../controller/mainController.php';

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
  private MainController $mainController;

  public function __construct()
  {
    $this->activePath = $this->getPath();
    $this->movieController = new MovieController();
    $this->userController = new UserController();
    $this->mainController = new MainController();
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
    $user = isset($_SESSION["login"]) ? unserialize($_SESSION['login']) : null;
    $templateEngine = new templateEngine(__DIR__ . '/templates');

    switch ($this->activePath) {
      // ─── GENERAL ─────────────────────────────────────────────────────
      case '/':
      case '/index.php':
        echo $templateEngine->render(
          'main.php',
          $this->mainController->getHomepage($user)
        );
        break;

      case '/about':
        echo $templateEngine->render(
          'main.php',
          $this->mainController->getAboutPage($user)
        );
        break;

      case '/contact':
        echo $templateEngine->render(
          'main.php',
          $this->mainController->getContactPage($user)
        );
        break;

      // ─── MOVIES ─────────────────────────────────────────────────────
      case '/collection':
        echo $templateEngine->render(
          'main.php',
          $this->movieController->getCollectionPage($user, $_GET['title'] ?? '', $_GET['orderby'] ?? 'id')
        );
        break;

      //TODO: make movie page
      case (preg_match("/\/collection\/(.*)/i", $this->activePath, $matches) ? true : false):
        echo $templateEngine->render(
          'main.php',
          $this->movieController->getMoviePage($user, $matches[1])
        );
        break;

      // ─── USER ─────────────────────────────────────────────────────
      case '/signup':
        if ($user != null) {
          header('Location: /');
        } else {
          echo $templateEngine->render(
            'main.php',
            $this->userController->getSignUpPage()
          );
        }

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
                $user != null ? null : '<a href="/register">Create an account</a> to get a more complete experience. With an account you can do, see and interact more!'
              ),
              ArticleModel::get('collection')
            ]
          ]
        );
        break;

      case '/login':
        if ($user != null) {
          header('Location: /');
        } else {
          echo $templateEngine->render(
            'main.php',
            isset($_POST['submit']) ? $this->userController->getLoginPage($_POST['username'], $_POST['password']) : $this->userController->getLoginPage()
          );
        }
        break;

      case '/logout':
        unset($_SESSION['login']);
        header('Location: /');
        break;

      case '/forgot':
        echo $templateEngine->render(
          'main.php',
          $this->userController->getForgotPage()
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
                $user != null ? null : '<a href="/register">Create an account</a> to get a more complete experience. With an account you can do, see and interact more!'
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
                $user != null ? null : '<a href="/register">Create an account</a> to get a more complete experience. With an account you can do, see and interact more!'
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
