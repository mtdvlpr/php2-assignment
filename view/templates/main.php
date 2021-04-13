<?php
include_once __DIR__ . '/../components/nav.php';
include_once __DIR__ . '/../components/article.php';
include_once __DIR__ . '/../components/form.php';
include_once __DIR__ . '/../components/movieArticle.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $title; ?></title>
        <?php include __DIR__ . '/../head.html';?>
    </head>

    <body>
        <header>
            <img src="/img/icons/logo.svg" alt="Logo" />
        </header>
        <section class="content">
            <?php
                $nav = new Nav();

                if (isset($_SESSION["login"])) {
                    $nav->render(
                        $_SESSION["login"]["role"],
                        $_SESSION["login"]["name"],
                        $_SESSION["login"]["img"]
                    );
                } else {
                    $nav->render();
                }
            ?>
            <main>
                <aside>
                    <?php
                        foreach ($asideArticles as $articleModel) {
                            $article = new Article($articleModel);
                            $article->render();
                        }
                    ?>
                </aside>
                <section class="leftcolumn">
                    <?php
                        foreach ($mainArticles as $model) {
                            $class = str_replace('Model', '', get_class($model));
                            $article = new $class($model);
                            $article->render();
                        }
                    ?>
                </section>
            </main>
        </section>
        <img src="/img/scrollBack.svg" id="scrollbtn" onclick="topFunction()" alt="Scroll Back" />
        <footer>
            <a href="/">Home</a> |
            <a href="/about">About Us</a> |
            <a href="/contact">Contact Us</a> |
            <a href="/collection">Our Collection</a> |
            <a href="/sitemap.html" target="_blank">Sitemap</a> |
            <a href="https://www.termsfeed.com/live/e8346890-0635-4127-920e-55f9a9bda7dc" target="_blank">Privacy Policy</a>
            <h3><small>Copyright &copy; <?php echo date("Y"); ?> <strong>Movies For You</strong></small></h3>
        </footer>
    </body>
</html>
