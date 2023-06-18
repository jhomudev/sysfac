<?php

$request = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (isset($_SESSION['token'])) {
  require_once "../Controllers/DataGraphicController.php";
  $IDG = new DataGraphicController();

  $message = "";
  if (isset($_POST["graphic"])) {
    switch ($_POST["graphic"]) {
      case 'graphicSales':
        $data = $IDG->getSalesAndPurchasesController();
        echo $data;
        break;
      case 'graphicStates':
        $data = $IDG->getProductsForStatesController();
        echo $data;
        break;
      case 'graphicBestSelling':
        $data = $IDG->getProductsBestController();
        echo $data;
        break;
    }
  }
} else {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}
