<?php
require_once __DIR__ . '/../db/movieDB.php';
require_once __DIR__ . '/../model/movieArticle.php';

class MovieController {
  private MovieDB $movieDB;

  public function __construct()
  {
    $this->movieDB = new MovieDB();
  }

  public function getCollection(string $title = '', string $orderBy = 'id') {
    return new MovieArticleModel($this->movieDB->getMovies($title, $orderBy));
  }
}
