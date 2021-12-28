<?php
/**
 * A template engine for PHP
 */
class templateEngine
{
  public function __construct(
  private string $templateFolder
  )
  {
    $this->templateFolder = rtrim($templateFolder, '/').'/';
  }

  /**
   * Render a view with the provided parameters
   *
   * @param string $view - The template to render.
   * @param array $context
   * @return string The populated template.
   */
  public function render(
    string $view,
    array $context = []
  ): string
  {
    // Check if the file exists
    if (!file_exists($file = $this->templateFolder.$view)) {
      throw new Exception(sprintf('The file %s could not be found.', $view));
    }

    // Extract the array values into variables
    extract(array_merge($context, ['template' => $this]));

    // Start buffering the output
    ob_start();

    // Populate the template
    include($file);

    // Return the buffered output
    return ob_get_clean();
  }
}
