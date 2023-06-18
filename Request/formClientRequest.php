<?php

$request = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (isset($_SESSION['token'])) {
  require_once "../Controllers/ClientController.php";
  $IC = new ClientController();

  if (!empty($_POST['tx_client_id'])) echo $IC->editClientController();
  // print_r($_POST);
} else {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}
