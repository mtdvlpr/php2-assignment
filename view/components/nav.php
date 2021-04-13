<?php

/**
 * The view component for the navigation bar of the website.
 */
class Nav
{
    public function __construct()
    {
    }

    public function render(int $role = -1, ?string $name = null, ?string $img = null): void
    {
        $dynamicMenu = $this->getDynamicMenu($role, $name, $img);
        echo /*html*/"
            <nav id='myTopnav'>
                <a href='/' class='active'>Home</a>
                <a href='about'>About Us</a>
                <a href='/contact'>Contact Us</a>
                <a href='/collection'>Our Collection</a>
                $dynamicMenu
                <a id='menu-icon' class='icon'><img src='/img/menu.svg' alt='Menu'></a>
            </nav>
        ";
    }

    private function getDynamicMenu(int $role, ?string $name, ?string $img): string
    {
        switch($role) {
            case -1:
                return /*html*/'<a href="/login" style="float:right">Log in</a>';
            case 0:
                return /*html*/"
                    <ul class='dropdown'>
                        <button class='dropbtn'>
                            $name
                            <img src='$img' alt='Profile Picture' class='profilepic'/>
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
                    <ul class='dropdown'>
                        <button class='dropbtn'>
                            $name
                            <img src='$img' alt='Profile Picture' class='profilepic'/>
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
