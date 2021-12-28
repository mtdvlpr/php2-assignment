<?php
  include_once __DIR__ . '/../components/nav.php';
  include_once __DIR__ . '/../components/article.php';
  include_once __DIR__ . '/../components/twitterFeed.php';
  include_once __DIR__ . '/../components/form.php';
  include_once __DIR__ . '/../components/collection.php';
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
      <?php
      if (isset($verifyContent)) {
        echo "<h1 class='h2' style='margin-top: 50px'>$verifyContent</h1>";
      }
      ?>
      <aside>
        <?php
        $importForm = null;
        foreach ($asideArticles as $articleModel) {
          if ($articleModel instanceof FormModel) {
            $importForm = $articleModel;
          } else {
            $article = new Article($articleModel);
            $article->render();
          }
        }
        if ($importForm != null && $user != null && $user->getRole() > 0) {
          echo /*html*/'
            <article class="form-container responsive">
              <h1 class="h4">Import movies</h1>';

          if ($importMsg != null) {
            $importMsg = match ($importClass) {
              ' class="error"' => '<i class="fa fa-times-circle"></i> ' . $importMsg,
              ' class="success"' => '<i class="fa fa-check"></i> ' . $importMsg,
              default => $importMsg
            };
            echo "<p$importClass>$importMsg</p>";
          }
          echo /*html*/ '
              <form method="post" enctype="multipart/form-data">
                <section class="row">
                  <section class="col-20">
                    <label for="">Movies CSV file</label>
                  </section>
                  <section class="col-60">
                    <input type="file" id="moviesFile" name="moviesFile" class="input-pic" required>
                    <label for="moviesFile"><span>Choose a file...</span></label>
                  </section>
                </section>
                <section class="row">
                  <button type="submit" name="import" class="submit green">Import movies</button>
                </section>
              </form>
            </article>
          ';
        }
        ?>
      </aside>
      <section class="leftcolumn">
        <?php
        foreach ($mainArticles as $model) {
          $class = str_replace('Model', '', $model != null ? get_class($model) : 'MovieModel');

          if (isset($_POST['refreshTweets']) && $class == 'TwitterFeed') {
            $_SESSION['tweets'] = $model->getTweets();
            header('Location: /#twitter-feed');
          }

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
