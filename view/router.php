<?php
session_start();
require_once __DIR__ . '/templateEngine.php';

// Controllers
require_once __DIR__ . '/../controller/movieController.php';
require_once __DIR__ . '/../controller/paymentController.php';
require_once __DIR__ . '/../controller/userController.php';
require_once __DIR__ . '/../controller/mainController.php';
require_once __DIR__ . '/../controller/adminController.php';
require_once __DIR__ . '/../controller/apiController.php';

// Models
require_once __DIR__ . '/../model/article.php';
require_once __DIR__ . '/../model/form.php';
require_once __DIR__ . '/../model/field.php';
require_once __DIR__ . '/../model/donation.php';

// Views
require_once __DIR__ . '/components/field.php';

/**
 * The router, this is responsible for showing the user the correct View.
 */
class Router
{
  private string $activePath;
  private MovieController $movieController;
  private UserController $userController;
  private MainController $mainController;
  private AdminController $adminController;
  private PaymentController $paymentController;
  private ApiController $apiController;

  public function __construct()
  {
    $this->activePath = $this->getPath();
    $this->movieController = new MovieController();
    $this->userController = new UserController();
    $this->mainController = new MainController();
    $this->adminController = new AdminController();
    $this->paymentController = new PaymentController();
    $this->apiController = new ApiController();
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
      // ????????? GENERAL ???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
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
        $context = [];

        if (isset($_POST['submit'])) {
          $context = $this->mainController->getContactPage(
            $user,
            $_POST['email'] ?? null,
            $_POST['name'] ?? null,
            $_POST['subject'] ?? null,
            $_POST['msg'] ?? null,
            $_POST['g-recaptcha-response']
          );
        } else {
          $context = $this->mainController->getContactPage($user);
        }

        echo $templateEngine->render(
          'main.php',
          $context
        );
        break;

      // ????????? MOVIES ???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
      case '/collection':
        echo $templateEngine->render(
          'main.php',
          $this->movieController->getCollectionPage($user, $_GET['title'] ?? '', $_GET['orderby'] ?? 'id', isset($_POST['import']) ? $_FILES : null)
        );
        break;

      case (preg_match("/\/collection\/(.*)/i", $this->activePath, $matches) ? true : false):
        echo $templateEngine->render(
          'main.php',
          $this->movieController->getMoviePage($user, $matches[1])
        );
        break;

      // ????????? USER ???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
      case '/signup':
        if ($user != null) {
          header('Location: /');
        } else {
          echo $templateEngine->render(
            'main.php',
            isset($_POST['submit']) ? $this->userController->getSignUpPage($_POST['username'], $_POST['name'], $_POST['password'], $_POST['confirm'], $_POST['g-recaptcha-response']) : $this->userController->getSignUpPage()
          );
        }

        break;

      case '/verify':
        echo $templateEngine->render(
          'main.php',
          $this->userController->getVerifyPage(
            $_GET["email"] ?? '',
            $_GET["hash"] ?? '',
            $_GET["new"] ?? '',
            isset($_POST["verify"])
          )
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
        if ($user != null) {
          header('Location: /');
        } else {
          echo $templateEngine->render(
            'main.php',
            isset($_POST['submit']) ? $this->userController->getForgotPage($_POST['email'], $_POST['confirm']) : $this->userController->getForgotPage()
          );
        }
        break;

      case '/account':
        if ($user == null) {
          header('Location: /login');
        } else {
          $context = $this->userController->getAccountsPage($user);

          // If update profile was clicked
          if (isset($_POST['update'])) {
            $updatedUser = new UserModel(
              $_POST['name'],
              $_POST['email'],
              $_POST['newPass']
            );

            $context = $this->userController->getAccountsPage(
              $user,
              false,
              null,
              $_POST['oldPass'],
              $updatedUser,
              $_POST['confirm'],
              $_FILES
            );
          }

          // If Remove picture was clicked
          else if (isset($_POST['removePic'])) {
            $context = $this->userController->getAccountsPage(
              $user,
              true
            );
          }

          // If Remove account was clicked
          else if (isset($_POST['removeAccount'])) {
            $context = $this->userController->getAccountsPage(
              $user,
              false,
              $_POST['usname'],
              $_POST['password']
            );
          }

          echo $templateEngine->render(
            'account.php',
            $context
          );
        }
        break;

      case '/admin':
        if ($user == null) {
          header('Location: /login');
        } else if ($user->getRole() < 1) {
          require_once __DIR__ . '/../public/403.shtml';
        } else {
          $searchMail = $_GET['searchMail'] ?? '';
          $searchName = $_GET['searchName'] ?? '';
          $regDate = $_GET['regDate'] ?? '';
          $context = $this->adminController->getAdminPage($user, $searchMail, $searchName, $regDate);

          if (isset($_POST['changeAdmin'])) {
            $context = $this->adminController->getAdminPage(
              $user,
              $searchMail,
              $searchName,
              $regDate,
              null,
              $_POST['username']
            );
          } else if (isset($_POST['addUser'])) {
            $newUser = new UserModel($_POST['name'], $_POST['email'], $_POST['password']);
            $context = $this->adminController->getAdminPage($user, $searchMail, $searchName, $regDate, $newUser);
          } else if (isset($_POST['removeUser'])) {
            $context = $this->adminController->getAdminPage(
              $user,
              $searchMail,
              $searchName,
              $regDate,
              null,
              $_POST['email'],
              $_POST['password']
            );
          } else if (isset($_POST['update'])) {
            $updatedUser = new UserModel(
              $_POST['name'],
              $_POST['newemail'],
              $_POST['newPass']
            );

            $context = $this->adminController->getAdminPage(
              $user,
              $searchMail,
              $searchName,
              $regDate,
              null,
              $_POST['usname'],
              $_POST['admin'],
              $updatedUser,
              $_POST['confirm'],
              $_FILES
            );
          } else if (isset($_POST['exportUsers'])) {
            $this->adminController->exportUsers($user, $_POST['format'] ?? 'csv', $_POST['role'] ?? 'true', $_POST['reg'] ?? 'true');
          }

          echo $templateEngine->render(
            'admin.php',
            $context
          );
        }
        break;

      // ????????? DONATION ???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
      case '/donate':

        if (isset($_POST['submit'])) {

          // Handle the form field input
          $email = $_POST['email'] ?? $user->getUsername();
          $name = $_POST['name'] ?? $user->getName();
          $amount = $_POST['amount'];
          $paymentMethod = $_POST['method'] ?? 'ideal';

          $donation = new DonationModel(1, new DateTime(), $amount, $name, $email);

          $this->paymentController->createDonation($donation, $paymentMethod);
        }

        echo $templateEngine->render(
          'main.php',
          $this->mainController->getDonatePage($user)
        );
        break;

      case '/donation':
        $donationId = $_GET['donation'] ?? 0;
        $hash = $_GET['hash'] ?? '';

        $valid = $this->paymentController->validateDonation($donationId, $hash);

        if ($valid) {
          header('Accept-Ranges: bytes');
          header('Content-type: application/pdf');
          header('Content-Transfer-Encoding: binary');
          header('Content-Disposition: inline; filename="Donation' . $donationId . '.pdf"');

          @readfile(__DIR__ . "/../src/pdf/donation" . $donationId . ".pdf");
        } else {
          echo $templateEngine->render(
            'donation.php',
            [
              "title" => "Donation Receipt",
              "user" => $user,
              "asideArticles" => [
                ArticleModel::get('about'),
                ArticleModel::get('collection')
              ],
              "orderId" => $donationId,
              "status" => 'invalidURL'
            ]
          );
        }
        break;

      case (preg_match("/^\/donation\/(.*)/i", $this->activePath, $matches) ? true : false):
        $data = $this->paymentController->getDonationById($matches[1]);

        echo $templateEngine->render(
          'donation.php',
          [
            "title" => "Donation",
            "user" => $user,
            "asideArticles" => [
              ArticleModel::get('about'),
              ArticleModel::get('collection')
            ],
            "orderId" => $matches[1],
            "status" => $data->getStatus()
          ]
        );
        break;

      // ????????? API ???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
      case (preg_match("/^\/api\/(.*)/i", $this->activePath, $matches) ? true : false):
        echo $this->apiController->getResponse($user, $matches[1]);
        break;

      default:
        require_once __DIR__ . "/404.php";
    }
  }
}
