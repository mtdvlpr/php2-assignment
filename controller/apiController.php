<?php
// Models
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/../model/movie.php';
require_once __DIR__ . '/../db/userDB.php';
require_once __DIR__ . '/../db/movieDB.php';

class ApiController
{
  private UserDB $userDB;
  private MovieDB $movieDB;

  public function __construct()
  {
    $this->userDB = new UserDB();
    $this->movieDB = new MovieDB();
  }

  public function getResponse(?UserModel $user, string $entity): string
  {
    header('Content-type: text/javascript');

    $response = null;
    $API_KEY = getallheaders()['Api-Key'] ?? null;
    $error = array('timestamp' => date("Y-m-d H:i:s"));
    $unauthorized = array('timestamp' => date("Y-m-d H:i:s"), 'error' => '401', 'message' => 'You are not authorized to perform this action.');

    if (str_starts_with($entity, 'users') && $user == null && $API_KEY == null) {
      http_response_code(401);
      $response = $unauthorized;
    } else if (str_starts_with($entity, 'users') && $user != null && $user->getRole() < 1) {
      http_response_code(401);
      $response = $unauthorized;
    } else if (str_starts_with($entity, 'users') && $API_KEY != null && $API_KEY != 'a02e3c9a-ba5c-412b-8ed6-36639ded60d0') {
      http_response_code(401);
      $response = $unauthorized;
    } else if ($entity == 'users') {
      $response = $this->userDB->getUsers($user->getRole(), '', '', '', false);
    } else if (preg_match("/^\/users\/(.*)/i", '/' . $entity, $matches)) {
      $response = $this->getUserById($user, $matches[1]);
      if ($response == null) {
        http_response_code(404);
        $response = $error + array('error' => '404', 'message' => 'User with id ' . $matches[1] . ' could not be found.');
      }
    } else if ($entity == 'movies') {
      $response = $this->movieDB->getMovies('', 'id', false);
    } else if (preg_match("/^\/movies\/(.*)/i", '/' . $entity, $matches)) {
      $response = $this->getMovieById($user, $matches[1]);
      if ($response == null) {
        http_response_code(404);
        $response = $error + array('error' => '404', 'message' => 'Movie with id ' . $matches[1] . ' could not be found.');
      }
    } else {
      return $entity;
    }

    return json_encode($response,  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
  }

  private function getUserById(?UserModel $user, string $id): array|null
  {
    $users = $this->userDB->getUsers($user?->getRole() ?? 1, '', '', '', false);

    foreach ($users as $user) {
      if ($user['id'] == (int)$id) {
        return $user;
      }
    }
    return null;
  }

  private function getMovieById(?UserModel $user, string $id): array|null
  {
    return $this->movieDB->getMovieById((int)$id, true);
  }
}
