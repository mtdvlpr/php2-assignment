<?php
require_once __DIR__ . '/baseDB.php';
require_once __DIR__ . '/../model/movie.php';

class MovieDB extends BaseDB
{
    public function __construct()
    {
    }

    /**
     * Get movies from the database
     *
     * @param string $title The title of the movie that is searched for
     * @param string $orderBy The field that the result should be ordered by
     *
     * @return array The result of the query in the form of an associative array
     */
    public function getMovies(string $title = "", string $orderBy = "id"): array
    {
        if ($orderBy != 'title' && $orderBy != 'score') {
          $orderBy = 'id';
        }

        $query = "SELECT id, title, release_date, director, category, runtime, score, `image` FROM movies WHERE title LIKE ? ORDER BY $orderBy";
        $result = $this->executeQueryList(
          $query,
          's',
          ["%$title%"]
        );

      $movies = [];

      foreach ($result->fetch_all(MYSQLI_ASSOC) as $row)
      {
        $movies[] = new MovieModel(
          $row['id'],
          $row['title'],
          $row['director'],
          $row['category'],
          $row['release_date'],
          $row['runtime'],
          $row['score'],
          $row['image']
        );
      }

      return $movies;
    }

    /**
     * Select a specific movie based on it's id
     *
     * @param int $id the id of the movie
     *
     * @return array The result of the query in the form of an associative array
     */
    public function getMovieById(int $id): MovieModel
    {
        $query = "SELECT id, title, release_date, director, category, runtime, score, `image` FROM movies WHERE id = ?";

        $this->executeQuery(
          $query,
          'i',
          [$id],
          $id,
          $title,
          $releaseDate,
          $director,
          $category,
          $runtime,
          $score,
          $image
        );

        return new MovieModel(
          $id,
          $title,
          $director,
          $category,
          $releaseDate,
          $runtime,
          $score,
          $image
        );
    }
}
