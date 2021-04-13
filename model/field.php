<?php

/**
 * The data model for a input field of a form on the website.
 */
class FieldModel
{
  public function __construct(
    private string $label,
    private string $id,
    private string $name,
    private ?string $placeholder = null,
    private string $type = 'text',
    private bool $required = true
  ) {
  }

  /**
   * Get the value of label
   */
  public function getLabel(): string
  {
    return $this->label;
  }

  /**
   * Set the value of label
   */
  public function setLabel(string $label): void
  {
    $this->label = $label;
  }

  /**
   * Get the value of id
   */
  public function getId(): string
  {
    return $this->id;
  }

  /**
   * Set the value of id
   */
  public function setId(string $id): void
  {
    $this->id = $id;
  }

  /**
   * Get the value of name
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Set the value of name
   */
  public function setName(string $name): void
  {
    $this->name = $name;
  }

  /**
   * Get the value of placeholder
   */
  public function getPlaceholder(): string | null
  {
    return $this->placeholder;
  }

  /**
   * Set the value of placeholder
   */
  public function setPlaceholder(string $placeholder): void
  {
    $this->placeholder = $placeholder;
  }

  /**
   * Get the value of type
   */
  public function getType(): string
  {
    return $this->type;
  }

  /**
   * Set the value of type
   */
  public function setType(string $type): void
  {
    $this->type = $type;
  }

  /**
   * Get the value of required
   */
  public function getRequired(): bool
  {
    return $this->required;
  }

  /**
   * Set the value of required
   */
  public function setRequired(bool $required): void
  {
    $this->required = $required;
  }
}
