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
    private string $submitName,
    private string $submitValue,
    private bool $hasCaptcha,
    ?string $content = null,
    ?string $extraContent = null,
    private string $method = 'post'
  ) {
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
   * Get the value of submitName
   */
  public function getSubmitName(): string
  {
    return $this->submitName;
  }

  /**
   * Set the value of submitName
   */
  public function setSubmitName(string $submitName): void
  {
    $this->submitName = $submitName;
  }

  /**
   * Get the value of submitValue
   */
  public function getSubmitValue(): string
  {
    return $this->submitValue;
  }

  /**
   * Set the value of submitValue
   */
  public function setSubmitValue(string $submitValue): void
  {
    $this->submitValue = $submitValue;
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
}
