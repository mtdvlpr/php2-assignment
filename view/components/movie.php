<?php
require_once __DIR__ . '/../../model/movie.php';

/**
 * The view component for a movie on the website.
 */
class Movie
{
  public function __construct(
  private MovieModel $movie
  )
  {
  }

  public function render(): void
  {
    echo "<article>";

    $title = $this->movie->getTitle();
    $date = date("Y", strtotime($this->movie->getReleaseDate()));
    $director = $this->movie->getDirector();
    $category = $this->movie->getCategory();
    $runtime = $this->movie->getRuntime();
    $score = $this->movie->getScore();
    $img = $this->movie->getImage();

    echo /*html*/"
      <h1 class='h4'>$title ($date)</h1>
      <img src='/$img' alt='$title'/>
      <p>Director: $director</p>
      <p>Category: $category</p>
      <p>Runtime: $runtime minutes</p>
      <p>Score: $score/10</p>
    ";

    echo "</article>";
  }
}
