<?php

$requestFetch = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (isset($_SESSION['token'])) {
  require_once "../Controllers/CategoryController.php";
  $IC = new CategoryController();

  if (empty($_POST['tx_category_id'])) print_r($IC->createCategoryController());
  else echo $IC->editCategoryController();
} else {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}