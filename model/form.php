<?php
require_once __DIR__ . '/article.php';

/**
 * The data model for an article on the website.
 */
class FormModel extends ArticleModel
{
  public function __construct(
    string $title,
  private array $fields,
  private string $submit,
  private bool $hasCaptcha,
    ?string $content = null,
  private string $contentClass = '',
    ?string $extraContent = null,
  private string $method = 'post',
  )
  {
    parent::__construct($title, $content, $extraContent);
  }

  /**
   * Get the value of fields
   */
  public function getFields(): array
  {
    return $this->fields;
  }

  /**
   * Set the value of fields
   */
  public function setFields(array $fields): void
  {
    $this->fields = $fields;
  }

  /**
   * Get the value of submit
   */
  public function getSubmit(): string
  {
    return $this->submit;
  }

  /**
   * Set the value of submit
   */
  public function setSubmit(string $submit): void
  {
    $this->submit = $submit;
  }

  /**
   * Get the value of hasCaptcha
   */
  public function getHasCaptcha(): bool
  {
    return $this->hasCaptcha;
  }

  /**
   * Set the value of hasCaptcha
   */
  public function setHasCaptcha(bool $hasCaptcha): void
  {
    $this->hasCaptcha = $hasCaptcha;
  }

  /**
   * Get the value of method
   */
  public function getMethod(): string
  {
    return $this->method;
  }

  /**
   * Set the value of method
   */
  public function setMethod(string $method): void
  {
    $this->method = $method;
  }

  /**
   * Get the value of contentClass
   */
  public function getContentClass(): string
  {
    return $this->contentClass;
  }

  /**
   * Set the value of contentClass
   */
  public function setContentClass(string $contentClass): void
  {
    $this->contentClass = $contentClass;
  }
}
