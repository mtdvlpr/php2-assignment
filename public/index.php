<?php
  require_once '../view/router.php';

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $router = new Router();
  $router->handleRoute();
