<?php

$request = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (empty($_SESSION['token'])) {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}

require_once "../Controllers/SellController.php";

$IS = new SellController();

$res = $IS->generateSellController();

echo $res;
// echo json_encode($_POST);
