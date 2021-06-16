<?php
  require_once '../view/router.php';
  require_once '../model/mailer.php';

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $router = new Router();
  $router->handleRoute();

  $mailer = new Mailer();
  $mailer->sendMail(
    'test',
    'test',
    '149895ja@gmail.com'
  )
