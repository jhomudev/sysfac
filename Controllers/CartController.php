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
    $product_id = MainModel::decryption($_POST['product_id']);
    $name = MainModel::decryption($_POST['name']);
    $price = MainModel::decryption($_POST['price']);
    $quantity = intval($_POST['quantity']);

    $ok_q =  is_numeric($quantity) && !empty($quantity) && $quantity > 0;
    if (!$ok_q) {
      $alert = [
        "Alert" => "simple",
        "title" => "cantidad inválida",
        "text" => "Por favor. Ingrese la cantidad." . gettype($quantity),
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // Validacion si los datos encriptados y desncruiptados son iguales
    $ok = is_numeric($product_id) && (is_string($name) && !empty($name)) && is_numeric($price);
    if (!$ok) {
      $alert = [
        "Alert" => "simple",
        "title" => "Acción restringida ",
        "text" => "Por favor. No modifique el código.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validicaion de que producto ya sta ahi
    foreach ($_SESSION['cart']['items'] as $key => $item) {
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

    $stm = CartModel::addItemModel(intval($product_id), $name, floatval($price), intval($quantity));

    if ($stm) {
      $alert = [
        "Alert" => "simple",
        "title" => "Añadido a carrito",
        "text" => "Producto añadido al carrito.",
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
    $product_id = intval($_POST['product_id']);
    $stm = CartModel::removeItemModel($product_id);

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
    // floatval($_POST['discount'])
    $discount = ($_POST['discount'] != "") ? floatval($_POST['discount']) : "";

    // validacion de descuento
    if ($discount == "" || is_string($discount)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Descuento no definido",
        "text" => "Determine el descuento a aplicar.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
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
    return number_format($total_import, 2);
  }

  public function getTotalPayController()
  {
    $total_import = CartModel::getTotalModel();
    $discount = $_SESSION['cart']['discount'];
    $total = $total_import - $discount;
    $total = $total + ((IGV / 100) * $total);
    return number_format($total, 2);
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
    $stm = CartModel::clearModel();
  }
}
