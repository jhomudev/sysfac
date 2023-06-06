<?php

if ($requestFetch) {
  require_once "./../Models/CartModel.php";
} else {
  require_once "./Models/CartModel.php";
}

class CartController extends CartModel
{
  public function __construct()
  {
    if (!isset($_SESSION['cart']['items'])) {
      $_SESSION['cart']['items'] = array();
      $_SESSION['cart']['discount'] = 0.00;
    }
  }

  public function addItemController()
  {
    $product_id = MainModel::clearString($_POST['tx_product_id']);
    $product = $this->executeQuerySimple("SELECT * FROM products WHERE product_id=$product_id")->fetch();
    $name = $product['name'];
    $price = $product['price_sale'];
    $add_for = $product['sale_for'];
    $ns = MainModel::clearString($_POST['tx_ns']);
    $quantity = MainModel::clearString(intval($_POST['tx_quantity']));
    $details = MainModel::clearString($_POST['tx_details']);


    if ($add_for == ADD_FOR->quantity) {
      $ok_q =  is_numeric($quantity) && !empty($quantity) && $quantity > 0;
      if (!$ok_q) {
        $alert = [
          "Alert" => "simple",
          "title" => "Cantidad inválida",
          "text" => "Por favor. Ingrese la cantidad.",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }

      // validicaion de que producto ya sta ahi
      foreach ($_SESSION['cart']['items'] as $item) {
        if ($item['product_id'] == $product_id) {
          $alert = [
            "Alert" => "simple",
            "title" => "Ya en carrito",
            "text" => "El producto ya está en el carrito.",
            "icon" => "warning"
          ];
          return json_encode($alert);
          exit();
        }
      }

      $stm = CartModel::addItemModel(intval($product_id), "", $name, floatval($price), intval($quantity), $details);
    } else if ($add_for == ADD_FOR->serial_number) {
      // validacion de campos ns vacio
      if (empty($ns)) {
        $alert = [
          "Alert" => "simple",
          "title" => "Numero(s) de serie no definido",
          "text" => "Por favor. Ingrese los números de serie.",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }

      // validacion de que producto numero de serie ya sta en carrito
      foreach ($_SESSION['cart']['items'] as $item) {
        if ($item['serial_number'] == $ns) {
          $alert = [
            "Alert" => "simple",
            "title" => "Ya en carrito",
            "text" => "El número de serie que ingresó ya esta en el carrito.",
            "icon" => "warning"
          ];
          return json_encode($alert);
          exit();
        }
      }

      $serial_numbers = explode(",", $ns);
      $serial_numbers = array_map(function ($num) {
        return trim($num);
      }, $serial_numbers);

      // validaion de si existe numero de serie y si es de ese producto
      $arr_val = [];
      foreach ($serial_numbers as $key => $ns) {
        $serial_number = MainModel::executeQuerySimple("SELECT serial_number FROM products_all WHERE serial_number='$ns' AND product_id=$product_id AND state=" . STATE_IN->stock)->fetchColumn();
        if ($serial_number) array_push($arr_val, $product);
      }

      if (count($serial_numbers) != count($arr_val)) {
        $alert = [
          "Alert" => "simple",
          "title" => "Error en números de serie",
          "text" => "Los números de serie ingresados no existen, no corresponden al producto o no estan disponibles",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }

      // validacion si numeros de serie son iguales o repiten
      if (count($serial_numbers) != count(array_unique($serial_numbers))) {
        $alert = [
          "Alert" => "simple",
          "title" => "Números de serie repetidos",
          "text" => "Los números de serie no deben repetirse",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }


      foreach ($serial_numbers as $key => $ns) {
        $stm = CartModel::addItemModel(intval($product_id), $ns, $name, floatval($price), 1, $details);
      }
    }

    if ($stm) {
      $alert = [
        "Alert" => "simple",
        "title" => "Añadido a carrito",
        "text" => "Producto(s) añadido al carrito.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps...",
        "text" => "No se añdió el proudcto al carrito.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }

  public function updateQuantityController($id, $new_quantity)
  {
    $stm = CartModel::updateQuantityModel($id, $new_quantity);
  }

  public function removeItemController()
  {
    $val = MainModel::clearString($_POST['val']);
    $col = MainModel::clearString($_POST['col']);

    $stm = CartModel::removeItemModel($col, $val);

    if ($stm) {
      $alert = [
        "Alert" => "simple",
        "title" => "Eliminado de carrito",
        "text" => "Producto eliminado del carrito.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opsss...",
        "text" => "El producto no se removió del carrito.",
        "icon" => "error"
      ];
    }
    return json_encode($alert);
    exit();
  }

  public function applyDiscountController()
  {
    // Validacion de carrito vacio
    $items = json_decode($this->getItemsController(), true);
    if (count($items) < 1) {
      $alert = [
        "Alert" => "simple",
        "title" => "Carrito vacio",
        "text" => "No se puede aplicar el descuento porque no hay ningún producto en el carrito.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validacion de tipo descuento
    if (empty($_POST['type']) || ($_POST['type'] != DISCOUNT->percentage && $_POST['type'] != DISCOUNT->absolute)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Elija el tipo de descuento",
        "text" => "Es necesario que seleccione el tipo de descuento a aplicar.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validacion de descuento
    if ($_POST['discount']  == "" || !is_numeric($_POST['discount'])) {
      $alert = [
        "Alert" => "simple",
        "title" => "Descuento no definido",
        "text" => "Determine el descuento a aplicar.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $discount = floatval($_POST['discount']);

    if (isset($_POST['type']) && !empty($_POST['type']) && $_POST['type'] == DISCOUNT->percentage) {
      $total = $this->getTotalImportController();
      $discount = ($discount / 100) * $total;
      $discount = round($discount, 2);
    }

    $stm = CartModel::applyDiscountModel($discount);

    if ($stm) {
      $alert = [
        "Alert" => "simple",
        "title" => "Descuento aplicado",
        "text" => "Se aplicó el descuento y actualizó el importe final.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opsss...",
        "text" => "No pudimos aplicar el descuento.",
        "icon" => "error"
      ];
    }
    return json_encode($alert);
    exit();
  }

  public function getItemsController()
  {
    $items = CartModel::getItemsModel();
    return json_encode($items);
  }

  public function getTotalImportController()
  {
    $total_import = CartModel::getTotalModel();
    return round($total_import, 2);
  }

  public function getTotalPayController()
  {
    $total_import = CartModel::getTotalModel();
    $discount = $_SESSION['cart']['discount'];
    $total = $total_import + ((IGV / 100) * $total_import);
    $total = $total - $discount;
    $total = round($total, 2);
    return $total;
  }

  public function getDataCartController()
  {
    $items_count = $this->getCountModel();
    $total_import = $this->getTotalImportController();
    $total_pay = $this->getTotalPayController();

    $data_cart = [
      "items_count" => $items_count,
      "discount" => $_SESSION['cart']['discount'],
      "total_import" => $total_import,
      "total_pay" => $total_pay,
    ];

    return json_encode($data_cart);
  }

  public function clearController()
  {
    CartModel::clearModel();
  }
}
