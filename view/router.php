<?php
session_start();

// Utils
require_once __DIR__ . "/templateEngine.php";

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
    $templateEngine = new templateEngine('/web/view/templates/');

    switch ($this->activePath) {
      case '/':
      case '/index.php':
        echo 'This is the homepage of the assignment.';
        break;

      default:
        echo 'Page not found...';
        break;
    }
  }
}
