<?php

$request = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (isset($_SESSION['token'])) {
  require_once "../Controllers/LocalController.php";
  $IL = new LocalController();

  if (empty($_POST['tx_local_id'])) echo $IL->createLocalController();
  else echo $IL->editLocalController();
} else {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}
