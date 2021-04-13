<?php

/**
 * The data model for an article on the website.
 */
class ArticleModel
{
  public function __construct(
    private string $title,
    private ?string $content,
    private ?string $extraContent = null
  )
  {
  }

  public static function get(string $type): self
  {
    switch ($type) {
      case 'contact':
        return new self(
          'Contact Us!',
          'Do not hesitate to <a href="/contact">contact us</a> for any questions, remarks or anything else.'
        );

      case  'about':
        return new self(
          'Movies For You',
          'We are dedicated to providing you with all the best movies!<br><a href="/about">Learn more</a>.'
        );

      case 'collection':
        return new self(
          'Check out our collection!',
          'We have collected a great number of amazing movies just for you! So, <a href="/collection">check out our movie collection</a>.'
        );
    }
  }

  /**
   * Get the value of title
   */
  public function getTitle(): string
  {
    return $this->title;
  }

  /**
   * Set the value of title
   */
  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  /**
   * Get the value of content
   */
  public function getContent(): string | null
  {
    return $this->content;
  }

  /**
   * Set the value of content
   */
  public function setContent(string $content): void
  {
    $this->content = $content;
  }

  /**
   * Get the value of extra content
   */
  public function getExtraContent(): string | null
  {
    return $this->extraContent;
  }

  /**
   * Set the value of extra content
   */
  public function setExtraContent(string $content): void
  {
    $this->extraContent = $content;
  }
}
