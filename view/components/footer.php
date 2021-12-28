<?php

/**
 * The view component for a footer on the website.
 */
class Footer
{
  public function __construct()
  {
  }

  public function render(): void
  {
    $date = date('Y');
    echo /*html*/"
      <img id='scroll-btn' src='/img/scrollBack.svg' alt='Scroll Back' />
      <footer>
        <a href='/'>Home</a> |
        <a href='/about'>About Us</a> |
        <a href='/contact'>Contact Us</a> |
        <a href='/collection'>Our Collection</a> |
        <a href='/sitemap.html' target='_blank'>Sitemap</a> |
        <a href='https://www.termsfeed.com/live/e8346890-0635-4127-920e-55f9a9bda7dc' target='_blank'>Privacy Policy</a>
        <h1 class='h4'><small>Copyright &copy; $date <strong>Movies For You</strong></small></h1>
      </footer>
      <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    ";
  }
}
