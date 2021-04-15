<?php
include_once __DIR__ . '/../components/nav.php';
include_once __DIR__ . '/../components/table.php';
include_once __DIR__ . '/../components/field.php';
include_once __DIR__ . '/../components/footer.php';
include_once __DIR__ . '/../components/header.php';

$selectOptions = new Field($field);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Admin page</title>
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
          <header>
            <h1 class='h3'>User List</h1>
          </header>
          <?php
          $table = new Table($tableModel);
          $table->render();
          ?>
        </article>
        <article class="form-container">
          <h1 class='h3'>Update User</h1>
          <p>Empty fields will remain unchanged.</p>
          <form method="post" autocomplete="off" enctype="multipart/form-data">
            <?php if (isset($_POST["update"]) && $_SESSION["login"]["role"] >= 1) {
              $controller->updateProfile($_POST["usname"], $_POST["admin"], $_POST["newemail"], $_POST["name"], $_POST["newPass"], $_POST["confirm"], $_SESSION["login"]["profile_picture"], true);
            } ?>
            <section class="row">
              <section class="col-20">
                <label for="adminpass">Admin Password <span>(required)</span></label>
              </section>
              <section class="col-60">
                <input type="password" id="adminpass" name="admin" placeholder="SecretPassword123!" required>
              </section>
            </section>
            <section class="row">
              <section class="col-20">
                <label for="email2">Username <span>(required)</span></label>
              </section>
              <section class="col-60">
                <select id="email2" name="usname" required>
                  <?php $selectOptions->render(); ?>
                </select>
              </section>
            </section>
            <section class="row">
              <section class="col-20">
                <label for="newemail">New Email</label>
              </section>
              <section class="col-60">
                <input type="email" id="newemail" name="newemail" placeholder="example@gmail.com">
              </section>
            </section>
            <section class="row">
              <section class="col-20">
                <label for="name2">Name</label>
              </section>
              <section class="col-60">
                <input type="text" id="name2" name="name" placeholder="Francesco de Bernardo">
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
              <button type="submit" name="update" class="submit">Update User</button>
            </section>
          </form>
        </article>
      </section>
      <aside>
        <article class="form-container responsive">
          <h1 class='h3'>Admin Actions</h1>
          <hr>
          <form method="post">
            <h2 class='h4'>Change Role</h2>
            <p>Admins lose their rights, users get rights.</p>
            <?php if (isset($_POST["changeAdmin"])) {
              $controller->changeAdmin($_POST["username"]);
            } ?>
            <section class="row">
              <section class="col-20">
                <label for="adminmail">Username</label>
              </section>
              <section class="col-60">
                <select id="adminmail" name="username" required>
                  <?php $selectOptions->render(); ?>
                </select>
              </section>
            </section>
            <section class="row">
              <button type="submit" name="changeAdmin" class="submit green">Change Role</button>
            </section>
            <hr>
          </form>
          <h2 class='h4'>Add user</h2>
          <?php if (isset($_POST["addUser"])) {
            $controller->register(strtolower($_POST["email"]), $_POST["name"], $_POST["password"], $_POST["password"], true);
          } ?>
          <form method="post">
            <section class="row">
              <section class="col-20">
                <label for="usname">Username</label>
              </section>
              <section class="col-60">
                <input type="email" id="usname" name="email" placeholder="example@gmail.com" required>
              </section>
            </section>
            <section class="row">
              <section class="col-20">
                <label for="name">Name</label>
              </section>
              <section class="col-60">
                <input type="text" id="name" name="name" placeholder="Francesco de Bernardo" required>
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
              <button type="submit" name="addUser" class="submit green">Add user</button>
            </section>
          </form>
          <hr>
          <h2 class='h4'>Remove user</h2>
          <form method="post" autocomplete="off">
            <?php if (isset($_POST["removeUser"])) {
              $controller->removeAccount($_POST["email"], $_POST["password"], true);
            } ?>
            <section class="row">
              <section class="col-20">
                <label for="email">Username</label>
              </section>
              <section class="col-60">
                <select id="email" name="email" required>
                  <?php $selectOptions->render(); ?>
                </select>
              </section>
            </section>
            <section class="row">
              <section class="col-20">
                <label for="admin">Admin Password</label>
              </section>
              <section class="col-60">
                <input type="password" id="admin" name="password" placeholder="SecretPassword123!" required>
              </section>
            </section>
            <section class="row">
              <button type="submit" name="removeUser" class='submit'>Remove User</button>
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
