<?php

if ($requestFetch) {
  require_once "./../Models/SellModel.php";
  require_once "./../Controllers/CartController.php";
} else {
  require_once "./Models/SellModel.php";
  require_once "./Controllers/CartController.php";
}

class SellController extends SellModel
{
  // Función controlador para obtener los usuarios
  public function getSellsController()
  {
    $sells = SellModel::getSellsModel();
    return json_encode($sells);
  }

  // Funcion controlador para crear o editar usuario
  public function generateSellController()
  {
    $ICart = new CartController();
    $cart_data = json_decode($ICart->getDataCartController());

    $client_id = $this->decryption($_POST['tx_client_id']);
    $user_id = $_SESSION['user_id'];
    $proof_type = (array_key_exists('tx_proof_type', $_POST)) ? intval($_POST['tx_proof_type']) : "";
    $discount = $cart_data->discount;
    $total_import = $cart_data->total_import;
    $total_pay = $cart_data->total_pay;

    // Validacion de carrito vacio
    $items = json_decode($ICart->getItemsController(), true);
    if (count($items) < 1) {
      $alert = [
        "Alert" => "simple",
        "title" => "Carrito vacio",
        "text" => "No se puede generar la venta porque no hay ningún producto en el carrito.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // Validacion de campos vacios
    if (empty($client_id) || empty($proof_type)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete los datos del cliente y seleccione el tipo de comprobante .",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // Validacion del id cliente
    if (!(is_numeric($client_id))) {
      $alert = [
        "Alert" => "simple",
        "title" => "Acción rechazada",
        "text" => "Por favor. No modifiqué el código.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }
    // Generar codigo de comprobante
    $last_id = MainModel::executeQuerySimple("SELECT sell_id FROM sells WHERE sell_id=(SELECT MAX(sell_id) FROM sells)")->fetchColumn(); // Implementa esta función para obtener el último número de boleta

    // Genera el siguiente número correlativo incrementando en 1 al último número de boleta
    if ($last_id) $new_number = $last_id + 1;
    else $new_number = 1;

    // Construye el código de boleta
    if ($proof_type == TYPE_PROOF->boleta) $letter = "B";
    else if ($proof_type == TYPE_PROOF->factura) $letter = "F";
    $proof_code = $letter . date("Y") . "-" . str_pad($new_number, 8, '0', STR_PAD_LEFT); // 'B' representa el prefijo y se completa con ceros a la izquierda si es necesario

    $data = [
      "sell_code" => "S-" . $user_id . uniqid(),
      "proof_code" => $proof_code,
      "client_id" => $client_id,
      "user_id" => $user_id,
      "discount" => $discount,
      "proof_type" => $proof_type,
      "total_import" => $total_import,
      "total_pay" => $total_pay,
      "created_at" =>  date('Y-m-d H:i:s'),
    ];

    $stm = SellModel::generateSellModel($data);

    if ($stm) {
      $ICart->clearController();
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Venta realizada",
        "text" => "La venta se registró exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "La venta no se registró. Intente de nuevo.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
}
