<?php

$requestFetch = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (isset($_SESSION['token'])) {
  require_once "../Controllers/UserController.php";
  $IU = new UserController();

  echo $IU->deleteUserController();
  // print_r($_POST);
} else {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}

// print_r($_POST);
