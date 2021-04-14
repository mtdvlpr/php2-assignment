<?php
  include_once __DIR__ . '/../components/nav.php';
  include_once __DIR__ . '/../components/article.php';
  include_once __DIR__ . '/../components/form.php';
  include_once __DIR__ . '/../components/movieArticle.php';
  include_once __DIR__ . '/../components/footer.php';
  include_once __DIR__ . '/../components/header.php';
  include_once __DIR__ . '/../components/movie.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title><?php echo $title; ?></title>
  <?php include __DIR__ . '/../components/head.html'; ?>
</head>

<body>
  <?php
    $header = new Header();
    $header->render();
  ?>
  <section class="content">
    <?php
    $nav = new Nav();
    $nav->render($user);
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
  <?php
    $footer = new Footer();
    $footer->render();
  ?>
</body>

</html>
