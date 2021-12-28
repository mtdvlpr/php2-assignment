<?php
class MovieModel
{
  public function __construct(
  private int $id,
  private string $title,
  private string $director,
  private string $category,
  private string $releaseDate,
  private int $runtime,
  private float $score,
  private string $image
  )
  {
  }

  /**
   * Get the value of id
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * Set the value of id
   */
  public function setId(int $id): void
  {
    $this->id = $id;
  }

  /**
   * Get the value of title
   */
  public function getTitle(): string
  {
    return htmlspecialchars($this->title);
  }

  /**
   * Set the value of title
   */
  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  /**
   * Get the value of director
   */
  public function getDirector(): string
  {
    return htmlspecialchars($this->director);
  }

  /**
   * Set the value of director
   */
  public function setDirector(string $director): void
  {
    $this->director = $director;
  }

  /**
   * Get the value of category
   */
  public function getCategory(): string
  {
    return htmlspecialchars($this->category);
  }

  /**
   * Set the value of category
   */
  public function setCategory(string $category): void
  {
    $this->category = $category;
  }

  /**
   * Get the value of releaseDate
   */
  public function getReleaseDate(): string
  {
    return $this->releaseDate;
  }

  /**
   * Set the value of releaseDate
   */
  public function setReleaseDate(string $releaseDate): void
  {
    $this->releaseDate = $releaseDate;
  }

  /**
   * Get the value of runtime
   */
  public function getRuntime(): int
  {
    return $this->runtime;
  }

  /**
   * Set the value of runtime
   */
  public function setRuntime(int $runtime): void
  {
    $this->runtime = $runtime;
  }

  /**
   * Get the value of score
   */
  public function getScore(): float
  {
    return $this->score;
  }

  /**
   * Set the value of score
   */
  public function setScore(float $score): void
  {
    $this->score = $score;
  }

  /**
   * Get the value of image
   */
  public function getImage(): string
  {
    return htmlspecialchars($this->image);
  }

  /**
   * Set the value of image
   */
  public function setImage(string $image): void
  {
    $this->image = $image;
  }
}
