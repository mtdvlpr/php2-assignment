<?php include 'base.php';?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $controller->writeHead("Error 404 Page Not Found");?>
    </head>
    <body>
        <?php $controller->showHeader();?>
        <section class="content">
            <nav id="myTopnav">
                <a href="/">Home</a>
                <a href="about">About Us</a>
                <a href="/contact">Contact Us</a>
                <a href="/collection">Our Collection</a>
                <?php $controller->showMenu(isset($_SESSION["login"]));?>
                <a class="icon" onclick="showMenu()"><img src="/img/menu.svg" alt="Menu"></a>
            </nav>

            <main>
                <section class="leftcolumn" style="width: 99%">
                    <article>
                        <header>
                            <h2>ERROR 404: PAGE NOT FOUND</h2>
                        </header>
                        <p>Oh no, it looks like you got lost...</p>
                        <p>Here are a couple of things you can do:</p>
                        <a href="/">go home</a> •
                        <a href="/contact">let us know what happened</a> •
                        <a href="/collection">check out our collection of movies</a>
                        </ul>
                    </article>
                </section>
            </main>
        </section>
        <?php $controller->showFooter();?>
    </body>
</html>