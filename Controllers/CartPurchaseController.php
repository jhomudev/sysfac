<?php

if ($requestFetch) {
  require_once "./../Models/CartPurchaseModel.php";
} else {
  require_once "./Models/CartPurchaseModel.php";
}

class CartPurchaseController extends CartPurchaseModel
{
  public function __construct()
  {
    if (!isset($_SESSION['cart_purchase']['items'])) {
      $_SESSION['cart_purchase']['items'] = array();
    }
  }

  public function addItemController()
  {
    // validacion precio vacio
    if (empty($_POST['tx_product_price'])  || empty($_POST['tx_product_id']) || empty($_POST['tx_product_profit'])) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Defina el producto, el costo de compra  y ganancia del producto.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $product_id = MainModel::clearString($_POST['tx_product_id']);
    $product = $this->executeQuerySimple("SELECT * FROM products WHERE product_id=$product_id")->fetch();
    $name = $product['name'];
    $price = MainModel::clearString($_POST['tx_product_price']);
    $profit = MainModel::clearString($_POST['tx_product_profit']);
    $add_for = $product['sale_for'];
    $ns = MainModel::clearString($_POST['tx_product_ns']);
    $quantity = MainModel::clearString(intval($_POST['tx_quantity']));


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

      // validacion de que producto ya sta ahi
      foreach ($_SESSION['cart_purchase']['items'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
          $alert = [
            "Alert" => "simple",
            "title" => "Ya en lista de compra",
            "text" => "El producto ya está en la lista de compra.",
            "icon" => "warning"
          ];
          return json_encode($alert);
          exit();
        }
      }

      $stm = CartPurchaseModel::addItemModel(intval($product_id), "", $name, floatval($price), intval($quantity), intval($profit));
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

      // validacion si numeros de serie son iguales o repiten en el input
      $serial_numbers = explode(",", $ns);
      $serial_numbers = array_map(function ($num) {
        return trim($num);
      }, $serial_numbers);

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

      // validacion si numeros de seria ya existen en la tabla products_all
      $ns_repeat = [];

      foreach ($serial_numbers as $ns) {
        $products_all = MainModel::executeQuerySimple("SELECT * FROM products_all WHERE serial_number='$ns'");
        if (count($products_all->fetchAll()) > 0) array_push($ns_repeat, $ns);
      }

      if (count($ns_repeat) > 0) {
        $alert = [
          "Alert" => "simple",
          "title" => "Números de serie existente ya en el sistema",
          "text" => "Uno o más de los numeros de serie que ingresó ya esta registrado a otro producto existente. N.S. que repiten :" . implode(' ', $ns_repeat),
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }

      // validacion si numeros de serie son iguales a uno q ya esta en la lista de compra
      $ns_repeat = [];/* array que contendra los ns iguales a los q ya estan */
      $items_cart_purchase = $_SESSION['cart_purchase']['items'];
      foreach ($serial_numbers as $key => $ns) {
        foreach ($items_cart_purchase as $key => $item) {
          if ($item['serial_number'] == $ns) array_push($ns_repeat, $ns);
        }
      }

      if (count($ns_repeat) > 0) {
        $alert = [
          "Alert" => "simple",
          "title" => "Números de serie ya registrados en la lista",
          "text" => "N.S. que repiten :".implode(' ', $ns_repeat),
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }


      foreach ($serial_numbers as $key => $ns) {
        $stm = CartPurchaseModel::addItemModel(intval($product_id), $ns, $name, floatval($price), 1, intval($profit));
      }
    }

    if ($stm) {
      $alert = [
        "Alert" => "simple",
        "title" => "Añadido a lista",
        "text" => "Producto(s) añadido al la lista de compra.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps...",
        "text" => "No se añadió el producto a la lista.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }

  public function removeItemController()
  {
    $val = MainModel::clearString($_POST['val']);
    $col = MainModel::clearString($_POST['col']);

    $stm = CartPurchaseModel::removeItemModel($col, $val);

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
        "title" => "Opsss..." . $val . $col,
        "text" => "El producto no se removió del carrito.",
        "icon" => "error"
      ];
    }
    return json_encode($alert);
    exit();
  }

  public function getItemsController()
  {
    $items = CartPurchaseModel::getItemsModel();
    return json_encode($items);
  }

  public function getTotalController()
  {
    $total_import = CartPurchaseModel::getTotalModel();
    return round($total_import, 2);
  }

  public function getDataCartPurchaseController()
  {
    $items = $this->getItemsModel();
    $total = $this->getTotalController();

    $data_cart_purchase = [
      "items" => $items,
      "total" => $total,
    ];

    return json_encode($data_cart_purchase);
  }

  public function clearController()
  {
    CartPurchaseModel::clearModel();

    if (count($this->getItemsModel()) == 0) {
      $alert = [
        "Alert" => "simple",
        "title" => "Lista de compra vacía",
        "text" => "La lista fue vaciada. Puede iniciar de nuevo.",
        "icon" => "success"
      ];
    }

    return json_encode($alert);
  }
}
