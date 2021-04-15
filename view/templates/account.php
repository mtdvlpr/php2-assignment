<?php
include_once __DIR__ . '/../components/nav.php';
include_once __DIR__ . '/../components/article.php';
include_once __DIR__ . '/../components/form.php';
include_once __DIR__ . '/../components/footer.php';
include_once __DIR__ . '/../components/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Your Account</title>
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
      <section class="leftcolumn">
        <article>
          <h1 class='h3'><?php echo $user->getName(); ?></h1>
          <img src='<?php echo $user->getProfilePicture(); ?>' alt='Profile Picture' style="width: 25%;" />
        </article>
        <article class="form-container">
          <h1 class='h3'>Change Account</h1>
          <?php
            foreach (explode(';', $updateFeedback) as $line) {
              echo $line;
            }
          ?>
          <form method="post" autocomplete="off" enctype="multipart/form-data">
            <section class="row">
              <section class="col-20">
                <label for="oldPass">Current Password <span>(required)</span></label>
              </section>
              <section class="col-60">
                <input type="password" id="oldPass" name="oldPass" placeholder="SecretPassword123!" required>
              </section>
            </section>
            <section class="row">
              <section class="col-20">
                <label for="email">Email Address</label>
              </section>
              <section class="col-60">
                <input type="email" id="email" name="email" placeholder="example@gmail.com">
              </section>
            </section>
            <section class="row">
              <section class="col-20">
                <label for="name">Name</label>
              </section>
              <section class="col-60">
                <input type="text" id="name" name="name" placeholder="Francesco de Bernardo">
              </section>
            </section>
            <section class="row">
              <section class="col-20">
                <label for="pass">New Password</label>
              </section>
              <section class="col-60">
                <input type="password" id="pass" name="newPass" minlength="8" placeholder="NewSecretPass123!">
              </section>
            </section>
            <section class="row">
              <section class="col-20">
                <label for="confirmpass">Confirm Password</label>
              </section>
              <section class="col-60">
                <input type="password" id="confirmpass" name="confirm" placeholder="NewSecretPass123!">
              </section>
            </section>
            <section class="row">
              <section class="col-20">
                <label>Profile Picture</label>
              </section>
              <section class="col-60">
                <input type="file" id="pic" name="pic" class="input-pic">
                <label for="pic"><span>Choose a file...</span></label>
              </section>
            </section>
            <section class="row">
              <button type="submit" class="submit" name="update">Update Profile</button>
            </section>
          </form>
        </article>
      </section>
      <aside>
        <article class="form-container responsive">
          <h1 class='h3'>Account Settings</h1>
          <hr>
          <h2 class='h4'>Remove Profile Picture</h2>
          <?php
          if ($pictureFeedback != null) {
            echo "<p$pictureClass>$pictureFeedback</p>";
          }
          ?>
          <form method="post">
            <section class="row">
              <button type="submit" class="submit" name="removePic">Remove Picture</button>
            </section>
          </form>
          <hr>
          <h2 class='h4'>Remove Account</h2>
          <form method="post" autocomplete="off">
            <?php
            if ($removeFeedback != null) {
              echo "<p$removeClass>$removeFeedback</p>";
            }
            ?>
            <section class="row">
              <section class="col-20">
                <label for="usname">Username</label>
              </section>
              <section class="col-60">
                <input type="email" id="usname" name="usname" placeholder="example@gmail.com" required>
              </section>
            </section>
            <section class="row">
              <section class="col-20">
                <label for="password">Password</label>
              </section>
              <section class="col-60">
                <input type="password" id="password" name="password" placeholder="SecretPassword123!" required>
              </section>
            </section>
            <section class="row">
              <button type="submit" class="submit" name="removeAccount">Remove account</button>
            </section>
          </form>
        </article>
      </aside>
    </main>
  </section>
  <?php
  $footer = new Footer();
  $footer->render();
  ?>
</body>

</html>
