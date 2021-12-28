<?php
include_once __DIR__ . '/components/nav.php';
include_once __DIR__ . '/components/footer.php';
include_once __DIR__ . '/components/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Error 404: not found</title>
  <?php include __DIR__ . '/components/head.html'; ?>
</head>

<body>
  <?php
    $header = new Header();
    $header->render();
  ?>
  <section class="content">
    <?php
    $nav = new Nav();
    $nav->render(isset($_SESSION['login']) ? unserialize($_SESSION['login']) : null);
    ?>
    <main>
      <section class="leftcolumn" style='width: 99%'>
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
  <?php
    $footer = new Footer();
    $footer->render();
  ?>
</body>

</html>
