<?php
/**
 * Class containing common helper methods.
 * They help with common usages and debugging.
 */
class UtilsController
{
  /**
   * Pretty print a array to the page.
   *
   * @param array $arr The array to print
   */
  static public function printArray(array $arr): void
  {
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
  }
}
?>
