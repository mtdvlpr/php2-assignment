<?php
require_once __DIR__ . '/../db/movieDB.php';
require_once __DIR__ . '/../model/collection.php';
require_once __DIR__ . '/../model/user.php';

class MovieController
{
  private MovieDB $movieDB;

  public function __construct()
  {
    $this->movieDB = new MovieDB();
  }

  public function getCollectionPage(?userModel $user, string $title = '', string $orderBy = 'id', ?array $fileArray = null): array
  {
    $msg = null;
    $class = ' class="error"';

    if ($fileArray != null && !empty($fileArray["moviesFile"]["name"])) {
      $targetDir = "img/uploads/";
      $fileName = basename($fileArray["moviesFile"]["name"]);
      $targetFilePath = $targetDir . $fileName;
      $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

      if ($fileType != 'csv') {
        $msg = 'Only .csv files are allowed.';
      } else if (move_uploaded_file($fileArray["moviesFile"]["tmp_name"], $targetFilePath)) {

        try {
          $this->importMovies($targetFilePath);
          $class = ' class="success"';
          $msg = 'The movies have been imported.';
        } catch (Exception $e) {
          $msg = $e->getMessage() . ' None of the movies were imported.';
        }
      } else {
        $msg = "There was an error while importing your .csv file.";
      }
    }

    return [
      "title" => "Our Collection",
      "user" => $user,
      "importMsg" => $msg,
      "importClass" => $class,
      "asideArticles" => [
        ArticleModel::get('about'),
        ArticleModel::get('contact'),
        new FormModel(
          'Import Movies',
          [
            new Field(
              new FieldModel(
                'Movies file',
                'moviesFile',
                'moviesFile',
                'Choose a file...',
                'file'
              )
            )
            ],
          'Import',
          false
        )
      ],
      "mainArticles" => [
        new FormModel(
          'Our Collection',
          [
            new Field(
              new FieldModel(
                'Search by title',
                'title',
                'title',
                'Star Wars',
                'text',
                false
              )
            ),
            new Field(
              new FieldModel(
                'Order by',
                'title;score',
                'orderby',
                null,
                'radio',
                false
              )
            )
          ],
          'Search',
          false,
          null,
          '',
          null,
          'get'
        ),
        new CollectionModel($this->movieDB->getMovies($title, $orderBy))
      ]
    ];
  }

  public function getMoviePage(?UserModel $user ,int $id): array
  {
    $movie = $this->movieDB->getMovieById($id);

    return [
      "title" => $movie?->getTitle() ?? 'Movie not found',
      "user" => $user,
      "asideArticles" => [
        ArticleModel::get('about'),
        ArticleModel::get('contact')
      ],
      "mainArticles" => [$movie]
    ];
  }

  private function importMovies(string $targetFile): void
  {
    $row = 1;
    $movies = array();
    $file = fopen($targetFile, 'r');
    while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
      array_push($movies, $this->convertLineToMovie($data, $row));
      $row++;
    }

    // Close the file and remove it from the server
    fclose($file);
    unlink($targetFile);

    // Add all movies to the database
    foreach ($movies as $movie) {
      $this->movieDB->addMovie($movie);
    }
  }

  private function convertLineToMovie(array $line, int $row): MovieModel
  {
    // Validate data
    if (!is_numeric($line[4])) {
      throw new Exception('The runtime value on row ' . $row . ' is not a number.');
    }

    if (!is_numeric($line[5])) {
      throw new Exception('The score value on row ' . $row . ' is not a number.');
    }

    try {
      $movie = new MovieModel(
        0,
        $line[0],
        $line[1],
        $line[2],
        $line[3],
        $line[4],
        $line[5],
        'img/movie-placeholder.png'
      );
      return $movie;
    } catch(Exception $e) {
      throw new Exception('The movie data on row ' . $row . ' is invalid.');
    }
  }
}
