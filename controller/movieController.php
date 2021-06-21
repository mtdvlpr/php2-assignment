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

  public function getCollectionPage(?userModel $user, string $title = '', string $orderBy = 'id'): array
  {
    return [
      "title" => "Our Collection",
      "user" => $user,
      "asideArticles" => [
        ArticleModel::get('about'),
        ArticleModel::get('contact')
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
}
