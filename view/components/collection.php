<?php
require_once __DIR__ . '/../../model/collection.php';

/**
 * The view component for a collection of movies on the website.
 */
class Collection
{
  public function __construct(
  private CollectionModel $collection
    )
  {
  }

  public function render(): void
  {
      echo "<article class='collection-article'>";

    foreach ($this->collection->getMovies() as $movie) {
      $id = $movie->getId();
      $title = $movie->getTitle();
      $img = $movie->getImage();
      $target = "window.location.href='/collection/$id'";
      $onclick = 'onclick="' . $target . '"';

        echo "
              <section class='movie' $onclick>
                <h1 class='h4'>$title</h1>
                <img src='$img' alt='$title'/>
              </section>
            ";
    }

      echo "</article>";
  }
}
