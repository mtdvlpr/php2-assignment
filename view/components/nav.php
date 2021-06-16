<?php
require_once __DIR__ . '/../../model/user.php';
/**
 * The view component for the navigation bar of the website.
 */
class Nav
{
  public function __construct()
  {
  }

  public function render(?UserModel $user, bool $isAccountPage = false): void
  {
    $dynamicMenu = null;
    if  ($user == null) {
      $dynamicMenu = $this->getDynamicMenu(-1);
    } else {
      $dynamicMenu = $this->getDynamicMenu($user->getRole(), $user->getName(), $user->getProfilePicture(), $isAccountPage);
    }
    echo /*html*/"
      <nav id='main-nav'>
          <a href='/'>Home</a>
          <a href='/about'>About Us</a>
          <a href='/contact'>Contact Us</a>
          <a href='/collection'>Our Collection</a>
          <a href='/donate'>Donate!</a>
          $dynamicMenu
          <a id='menu-icon' class='icon'><img src='/img/menu.svg' alt='Menu'></a>
      </nav>
    ";
  }

  private function getDynamicMenu(int $role, ?string $name = null, ?string $img = null, bool $isAccountPage = false): string
  {
    $dropdownClass = $isAccountPage ? 'dropdown active' : 'dropdown';
    switch($role) {
      case -1:
        return /*html*/'<a href="/login" style="float:right">Log in</a>';
      case 0:
        return /*html*/"
                    <ul class='$dropdownClass'>
                        <button class='drop-btn'>
                            $name
                            <img src='$img' alt='Profile Picture' class='profile-pic'/>
                            <i class='fa fa-caret-down'></i>
                        </button>
                        <li class='dropdown-content'>
                            <a href='/account'>Your account</a>
                            <a href='/logout'>Log out</a>
                        </li>
                    </ul>
                ";
      default:
        return /*html*/"
                    <ul class='$dropdownClass'>
                        <button class='drop-btn'>
                            $name
                            <img src='$img' alt='Profile Picture' class='profile-pic'/>
                            <i class='fa fa-caret-down'></i>
                        </button>
                        <li class='dropdown-content'>
                            <a href='/account'>Your account</a>
                            <a href='/admin'>Manage users</a>
                            <a href='/logout'>Log out</a>
                        </li>
                    </ul>
                ";
    }
  }
}
