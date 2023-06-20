<?php

$request = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (isset($_SESSION['token'])) {
  require_once "../Controllers/CartPurchaseController.php";
  require_once "../Controllers/PurchaseController.php";
  $ICart = new CartPurchaseController();
  $IP = new PurchaseController();

  if (isset($_POST["action"])) {

    switch ($_POST["action"]) {
      case 'Agregar':
        echo $ICart->addItemController();
        // echo json_encode($_POST);
        break;
      case 'getDataList':
        echo $ICart->getDataCartPurchaseController();
        break;
      case 'removeItem':
        echo $ICart->removeItemController();
        break;
      case 'clear':
        echo $ICart->clearController();
        break;
      case 'do':
        echo $IP->generatePurchaseController();
        break;
    }
  } else {
    echo "Accion no definida";
  }
} else {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}
