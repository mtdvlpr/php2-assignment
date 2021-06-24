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
     * @param bool $asMovieObjects Determine wether the return value should contain associative arrays or movie objects
     *
     * @return array The result of the query in the form of an associative array
     */
  public function getMovies(string $title = "", string $orderBy = "id", bool $asMovieObjects = true): array
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

    if (!$asMovieObjects) {
      return $result->fetch_all(MYSQLI_ASSOC);
    }

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
     * @param bool $asArray Decide if the return value should be a movie model or associative array
     *
     * @return MovieModel|array|null The result of the query in the form of an associative array or MovieModel or null if not found
     */
  public function getMovieById(int $id, bool $asArray = false): MovieModel|array|null
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

      if ($title == null) {
        return null;
      }

      if ($asArray) {
        return array(
          'id' => $id,
          'title' => $title,
          'release_date' => $releaseDate,
          'director' => $director,
          'category' => $category,
          'runtime' => $runtime,
          'score' => $score,
          'image' => $image
        );
      }

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

  /**
   * Add a movie to the database
   *
   * @param MovieModel $movie The movie object
   */
  public function addMovie(MovieModel $movie): void
  {
    $this->executeMutation(
      'INSERT INTO movies (`title`, release_date, `director`, category, runtime, score, `image`) VALUES (?, ?, ?, ?, ?, ?, ?)',
    'sssssss',
    [$movie->getTitle(), $movie->getReleaseDate(), $movie->getDirector(), $movie->getCategory(), $movie->getRuntime(), $movie->getScore(), $movie->getImage()]
  );
  }
}
