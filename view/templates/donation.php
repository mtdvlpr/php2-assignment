<?php
include_once __DIR__ . '/../components/nav.php';
include_once __DIR__ . '/../components/article.php';
include_once __DIR__ . '/../components/form.php';
include_once __DIR__ . '/../components/collection.php';
include_once __DIR__ . '/../components/footer.php';
include_once __DIR__ . '/../components/header.php';
include_once __DIR__ . '/../components/movie.php';

$body = [
  "paid" => [
    "title" => "Thank you",
    "description" => "Your donation #$orderId was successful, thank you very much! Your receipt will be send to your E-mail address."
  ],
  "authorized" => [
    "title" => "Thank you",
    "description" => "Your donation #$orderId was successful, thank you very much! Your receipt will be send to your E-mail address."
  ],
  "shipping" => [
    "title" => "Thank you",
    "description" => "Your donation #$orderId was successful, thank you very much! Your receipt will be send to your E-mail address."
  ],
  "completed" => [
    "title" => "Thank you",
    "description" => "Your donation #$orderId was successful, thank you very much! Your receipt will be send to your E-mail address."
  ],
  "created" => [
    "title" => "Processing...",
    "description" => "We are still processing your donation #$orderId. Check back later, if this takes longer then 15 minutes please contact support."
  ],
  "pending" => [
    "title" => "Processing...",
    "description" => "We are still processing your donation #$orderId. Check back later, if this takes longer then 15 minutes please contact support."
  ],
  "failed" => [
    "title" => "Oops...",
    "description" => "Something went wrong wile processing your donation #$orderId. Please try again, if this issue continues please contact support."
  ],
  "canceled" => [
    "title" => "Cancelled",
    "description" => "We are sad to see you have changed your mind. Your donation #$orderId has been cancelled."
  ],
  "expired" => [
    "title" => "Order expired",
    "description" => "The donation #$orderId has expired, if you change your mind create a new donation."
  ],
  "invalidURL" => [
    "title" => "Invalid Request",
    "description" => "Your url is invalid, go back home or try again."
  ]
];
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
        <article>
          <h1 class='h3'><?php echo $body[$status]['title']; ?></h1>
          <p><?php echo $body[$status]['description']; ?></p>
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
