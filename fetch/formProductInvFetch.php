<?php

$requestFetch = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (isset($_SESSION['token'])) {
  require_once "../Controllers/ProductController.php";
  $IP = new ProductController();

  echo $IP->editProductInventaryController();
  // print_r(json_encode($_POST));
} else {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}
