<?php

if ($request) {
  require_once "./../Models/SellModel.php";
  require_once "./../Controllers/CartController.php";
  require_once "./../Controllers/ClientController.php";
} else {
  require_once "./Models/SellModel.php";
  require_once "./Controllers/CartController.php";
  require_once "./Controllers/ClientController.php";
}

class SellController extends SellModel
{
  // Función controlador para obtener los ventas
  public function getSellsController(mixed $start = null, mixed $end = null)
  {
    $filters = [
      'words' => MainModel::getCleanGetValue('words'),
      'user_id' => MainModel::getCleanGetValue('user_id'),
      'date_start' => MainModel::getCleanGetValue('date_start'),
      'date_end' => MainModel::getCleanGetValue('date_end'),
    ];

    $sells = SellModel::getSellsModel($filters, $start, $end);
    return json_encode($sells);
  }

  // Función controlador para obtener datos de una venta
  public function getSellDataController()
  {

    $sell = SellModel::getDataSellModel($_GET['proof_code']);

    return json_encode($sell);
  }

  // Funcion controlador para crear o editar venta
  public function generateSellController()
  {
    $ICart = new CartController();
    $cart_data = json_decode($ICart->getDataCartController());

    $client_id = $this->decryption($_POST['tx_client_id']);
    $user_id = $_SESSION['user_id'];
    $proof_type = TYPE_PROOF->boleta;
    $discount = $cart_data->discount;
    $total_import = $cart_data->total_import;
    $total_pay = $cart_data->total_pay;

    // Validacion de carrito vacio
    $items = json_decode($ICart->getItemsController());
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

    // validacion campos vacios
    if (empty($_POST['tx_client_names']) || empty($_POST['tx_client_lastnames']) || (empty($_POST['tx_client_RUC']) && empty($_POST['tx_client_dni']))) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Complete los datos del cliente.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // Funcionalidaad de crear cliente si no existe
    if (empty($client_id)) {
      if ($proof_type == TYPE_PROOF->boleta && empty($_POST['tx_client_dni'])) {
        $alert = [
          "Alert" => "simple",
          "title" => "Campos vacios",
          "text" => "Usted generará una boleta de venta, escriba el DNI del cliente.",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }
      if ($proof_type == TYPE_PROOF->factura && empty($_POST['tx_client_RUC'])) {
        $alert = [
          "Alert" => "simple",
          "title" => "Campos vacios",
          "text" => "Usted generará una factura, escriba el RUC del cliente.",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }

      $IClient = new ClientController();
      $res = json_decode($IClient->createClientController());

      if ($res->icon == "warning" || $res->icon == "error") {
        $alert = [
          "Alert" => "simple",
          "title" => "Error al registrar cliente",
          "text" => "No pudimos registrar al cliente. Es posible que los datos DNI/RUC ya estén registrados a otro cliente.",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }

      if ($proof_type == TYPE_PROOF->boleta) {
        $client_id = MainModel::executeQuerySimple("SELECT client_id FROM clients WHERE dni =" . intval($_POST['tx_client_dni']))->fetchColumn();
      } else if ($proof_type == TYPE_PROOF->factura) {
        $client_id = MainModel::executeQuerySimple("SELECT client_id FROM clients WHERE RUC =" . intval($_POST['tx_client_RUC']))->fetchColumn();
      }
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
    if (!(is_string($client_id))) {
      $alert = [
        "Alert" => "simple",
        "title" => "Acción rechazada",
        "text" => "Por favor. No modifique el código.",
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
      // Funcion actualizar estado a vendido de los productos vendidos
      foreach ($items as $item) {
        if (empty($item->serial_number)) {
          MainModel::executeQuerySimple("UPDATE products_all SET state=" . STATE_IN->sold . " WHERE product_id=" . $item->product_id . " AND state=" . STATE_IN->stock . " LIMIT " . $item->quantity);
        } else {
          MainModel::executeQuerySimple("UPDATE products_all SET state=" . STATE_IN->sold . " WHERE serial_number='$item->serial_number'");
        }
      }

      $alert = [
        "Alert" => "alert&reload",
        "title" => "Venta realizada",
        "text" => "La venta se registró exitosamente.",
        "icon" => "success"
      ];
      $ICart->clearController();
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
