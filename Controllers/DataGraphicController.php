<?php

if ($request) {
  require_once "./../Models/DataGraphicModel.php";
} else {
  require_once "./Models/DataGraphicModel.php";
}

class DataGraphicController extends DataGraphicModel
{
  // Funcion controlador para obtener la data de ventas y compras
  public function getSalesAndPurchasesController()
  {
    $year = isset($_POST['year']) && !empty($_POST['year']) ? $_POST['year'] : date("Y");
    // $month = isset($_POST['month']) && !empty($_POST['month']) ? $_POST['month'] : date("m");

    if (empty($year)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Año no definido",
        "text" => "Por favor. Defina el año",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }


    $months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    $quantity = DataGraphicModel::getSalesAndPurchasesModel($year);

    $data = [
      "quantity" => $quantity,
      "quality" => $months,
    ];

    return json_encode($data);
  }

  // Funcion controlador para obtener los datos de cliente
  public function getProductsForStatesController()
  {
    $quantity = DataGraphicModel::getProductsForStatesModel();

    $data = [
      "quantity" => $quantity,
      "quality" => ["En stock", "Dañados", "Vendidos"],
    ];

    return json_encode($data);
  }

  // Funcion controlador para obtener la data de productos mas vendidos
  public function getProductsBestController()
  {
    $year = isset($_POST['year']) && !empty($_POST['year']) ? $_POST['year'] : date("Y");
    $month = isset($_POST['month']) && !empty($_POST['month']) ? $_POST['month'] : "";

    $data = DataGraphicModel::getProductsBestModel($year, $month);

    $quality = [];
    $quantity = [];

    foreach ($data as $key => $item) {
      array_push($quality, $item['product']);
      array_push($quantity, $item['quantity']);
    }

    $data = [
      "quantity" => $quantity,
      "quality" => $quality,
    ];

    return json_encode($data);
  }
}
