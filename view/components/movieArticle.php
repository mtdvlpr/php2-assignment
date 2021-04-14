<?php
require_once __DIR__ . '/../../model/movieArticle.php';

/**
 * The view component for a movie article on the website.
 */
class MovieArticle
{
  public function __construct(
  private MovieArticleModel $movieArticle
    )
  {
  }

  public function render(): void
  {
      echo "<article class='moviearticle'>";

    foreach ($this->movieArticle->getMovies() as $movie) {
      $id = $movie->getId();
      $title = $movie->getTitle();
      $img = $movie->getImage();
      $target = "window.location.href='/collection/$id'";

        echo "
              <section class='movie' onclick='$target'>
                <h1 class='h4'>$title</h1>
                <img src='$img' alt='$title'/>
              </section>
            ";
    }

      echo "</article>";
  }
}
