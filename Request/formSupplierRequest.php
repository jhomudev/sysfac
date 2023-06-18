<?php

$request = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (isset($_SESSION['token'])) {
  require_once "../Controllers/SupplierController.php";
  $IP = new SupplierController();

  if (empty($_POST['tx_supplier_id'])) echo $IP->createSupplierController();
  else echo $IP->editSupplierController();
} else {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}
