<?php

/**
 * The view component for a header on the website.
 */
class Header
{
  public function __construct()
  {
  }

  public function render(): void
  {
    $date = date('Y');
    echo /*html*/ "
      <header>
        <img src='/img/icons/logo.svg' alt='Movies For You' />
      </header>
    ";
  }
}
