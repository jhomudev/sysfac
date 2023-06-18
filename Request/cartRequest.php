<?php

$request = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (isset($_SESSION['token'])) {
  require_once "../Controllers/CartController.php";
  $ICart = new CartController();

  $message = "";
  if (isset($_POST["action"])) {

    switch ($_POST["action"]) {
      case 'Agregar':
        echo $ICart->addItemController();
        // echo json_encode(print_r($_POST));
        break;
      case 'getDataCart':
        echo $ICart->getDataCartController();
        break;
      case 'getCart':
        echo $ICart->getItemsController();
        break;
      case 'removeItem':
        echo $ICart->removeItemController();
        break;
      case 'gratify':
        echo $ICart->gratifyItemController();
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
