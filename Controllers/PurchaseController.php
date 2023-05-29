<?php

if ($requestFetch) {
  require_once "./../Models/PurchaseModel.php";
  require_once "./../Controllers/CartPurchaseController.php";
} else {
  require_once "./Models/PurchaseModel.php";
  require_once "./Controllers/CartPurchaseController.php";
}

class PurchaseController extends PurchaseModel
{
  // Función controlador para obtener las compras
  public function getPurchasesController()
  {
    $filters = [
      "column" => $_POST['column'],
      "value" => $_POST['value'],
      "start_date" => $_POST['date_start'],
      "end_date" => $_POST['date_end'],
    ];

    $purchases = PurchaseModel::getPurchasesModel($filters);
    return json_encode($purchases);
  }

  // Función controlador para obtener datos de una compra
  public function getDataPurchaseController()
  {
    $purchase = PurchaseModel::getDataPurchaseModel($_GET['purchase_id']);

    return json_encode($purchase);
  }

  // Funcion controlador para crear una compra
  public function generatePurchaseController()
  {
    $ICP = new CartPurchaseController();
    $purchase_data = json_decode($ICP->getDataCartPurchaseController());

    $supplier_id = $_POST['tx_supplier_id'];
    $additional_info = $_POST['tx_add_info'];
    $user_id = $_SESSION['user_id'];
    $purchase_items = json_decode($ICP->getItemsController());
    $purchase_total = $purchase_data->total;

    // Validacion de carrito/lista vacio
    if (count($purchase_items) < 1) {
      $alert = [
        "Alert" => "simple",
        "title" => "Lista de compra vacía",
        "text" => "No se puede generar la compra porque no hay ningún producto en la lista.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // Validacion de campos vacios
    if (empty($supplier_id)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete los datos del proveedor.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // Validacion del id cliente y supplier_id
    if (!(is_numeric($supplier_id))) {
      $alert = [
        "Alert" => "simple",
        "title" => "Acción rechazada",
        "text" => "Por favor. No modifiqué el código.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $data = [
      "purchase_code" => "P-" . $user_id . uniqid(),
      "user_id" => $user_id,
      "supplier_id" => $supplier_id,
      "total" => $purchase_total,
      "additional_info" => $additional_info,
      "created_at" =>  date('Y-m-d H:i:s'),
    ];

    $stm = PurchaseModel::generatePurchaseModel($data);

    if ($stm) {
      // FUNCION PARA INSERTAR A TABLA PRODUCTS_ALL
      foreach ($purchase_items as $item) {
        $product_id = $item->product_id;
        $serial_number = $item->serial_number;
        $quantity = $item->quantity;

        for ($i = 0; $i < $quantity; $i++) {
          if (empty($serial_number)) {
            $insert = MainModel::executeQuerySimple("INSERT INTO products_all (product_id,state) VALUES ($product_id," . STATE_IN->stock . ")");
          } else {
            $insert = MainModel::executeQuerySimple("INSERT INTO products_all (product_id,serial_number,state) VALUES ($product_id,'$serial_number'," . STATE_IN->stock . ")");
          }
        }
      }

      if ($insert->errorCode() === '00000' && $insert->rowCount() > 0) {
        // Actualizar precio de venta de los productos

        // Obtencion del precio x product_id, se obtiene un array con la data
        $arr_product_price = [];
        $existingIds = [];

        foreach ($purchase_items as $item) {
          $product_id = $item->product_id;
          $price_sale = $item->price_sale;

          if (!in_array($product_id, $existingIds)) {
            $existingIds[] = $product_id;
            $arr_product_price[] = [
              "product_id" => $product_id,
              "price_sale" => $price_sale
            ];
          }
        }
        $alert = $arr_product_price;
        // Actualizando precios de los productos de la compra
        foreach ($arr_product_price as $product) {
          $product_id = $product['product_id'];
          $price_sale = $product['price_sale'];

          MainModel::executeQuerySimple("UPDATE products SET price_sale=$price_sale WHERE product_id=$product_id");
        }

        $alert = [
          "Alert" => "simple",
          "title" => "Compra/abastecimiento realizado",
          "text" => "El abastecimiento se registró exitosamente.",
          "icon" => "success"
        ];
        // limpiar el carrito
        $ICP->clearController();
      } else {
        $alert = [
          "Alert" => "simple",
          "title" => "Productos no registrados",
          "text" => "Los nuevos productos no se pudieron registrar.",
          "icon" => "warning"
        ];
      }
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "El abastecimiento no se registró. Intente de nuevo.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
}
