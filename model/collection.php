<?php
require_once __DIR__ . '/article.php';

/**
 * The data model for a collection of movies on the website.
 */
class CollectionModel
{
  public function __construct(private array $movies)
  {
  }

  /**
   * Get the value of movies
   */
  public function getMovies(): array
  {
      return $this->movies;
  }

  /**
   * Set the value of movies
   */
  public function setMovies(array $movies): void
  {
      $this->movies = $movies;
  }
}
