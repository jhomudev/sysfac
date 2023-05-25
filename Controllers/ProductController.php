<?php

if ($requestFetch) {
  require_once "./../Models/ProductModel.php";
} else {
  require_once "./Models/ProductModel.php";
}

class ProductController extends ProductModel
{
  // Función controlador para obtener los productos
  public function getProductsController()
  {
    $filters = [
      "words" => isset($_POST['words']) ? MainModel::clearString($_POST['words']) : "",
      "column" => isset($_POST['column']) ? MainModel::clearString($_POST['column']) : "",
      "value" => isset($_POST['value:""']) ? MainModel::clearString($_POST['value']) : "",
    ];
    $products = ProductModel::getProductsModel($filters);

    return json_encode($products);
  }

  // Funcion controlador para obetenr los datos de producto
  public function getDataProductController()
  {
    $product_id = intval($_POST['productId']);
    $product = ProductModel::getDataProductModel($product_id);
    return json_encode($product);
  }

  // Función controlador para obtener todos los productos en inventario
  public function getProductsInventaryController()
  {
    $filters = [
      "words" => MainModel::clearString($_POST['words']),
      "column" => MainModel::clearString($_POST['column']),
      "value" => MainModel::clearString($_POST['value']),
    ];

    $products_all = ProductModel::getProductsInventaryModel($filters);

    foreach ($products_all as $key => $product_unit) {
      if ($products_all[$key]['state'] == STATE_IN->stock) $products_all[$key]['state'] = 'En stock';
      if ($products_all[$key]['state'] == STATE_IN->damaged) $products_all[$key]['state'] = 'Dañado';
      if ($products_all[$key]['state'] == STATE_IN->sold) $products_all[$key]['state'] = 'Vendido';
    }

    return json_encode($products_all);
  }

  // Funcion controlador para crear o editar producto
  public function createProductController()
  {
    $name = MainModel::clearString($_POST['tx_nombre']);
    $price = MainModel::clearString($_POST['tx_precio']);
    $unit = MainModel::clearString($_POST['tx_unidad']);
    $min = MainModel::clearString($_POST['tx_minimo']);
    $link_image = MainModel::clearString($_POST['tx_linkImage']);
    $sale_for = MainModel::clearString($_POST['tx_sale_for']);
    $category = MainModel::clearString($_POST['tx_category']);
    $is_active = intval($_POST['tx_activo']);

    // Validacion de campos vacios
    if (empty($price) || empty($name) || empty($unit) || empty($min) || empty($category) || !isset($is_active) || !isset($sale_for)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validación datos dupliacods, nombre de producto
    $sql_verify = MainModel::executeQuerySimple("SELECT * FROM products WHERE name='$name'");
    $products = $sql_verify->fetchAll();
    $duplicated = count($products) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El nombre de un producto único, no pueden repetirse.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $data = [
      "link_image" => $link_image,
      "name" => $name,
      "inventary_min" => $min,
      "price_sale" => $price,
      "unit" => $unit,
      "sale_for" => $sale_for,
      "category_id" => $category,
      "is_active" => $is_active,
      "created_at" =>  date('Y-m-d H:i:s'),
    ];

    $stm = ProductModel::createProductModel($data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Producto creado",
        "text" => "El producto se creó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "El producto no se creó.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
  // Funcion controlador para crear o editar producto
  public function editProductController()
  {
    $product_id = MainModel::clearString($_POST['tx_product_id']);
    $name = MainModel::clearString($_POST['tx_nombre']);
    $price = MainModel::clearString($_POST['tx_precio']);
    $unit = MainModel::clearString($_POST['tx_unidad']);
    $min = MainModel::clearString($_POST['tx_minimo']);
    $link_image = MainModel::clearString($_POST['tx_linkImage']);
    $sale_for = MainModel::clearString($_POST['tx_sale_for']);
    $category = MainModel::clearString($_POST['tx_category']);
    $is_active = intval($_POST['tx_activo']);

    // Valididación de campos vacios
    if (empty($name) || empty($price) || empty($unit) || empty($min) || empty($category) || !isset($is_active) || !isset($sale_for)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validación de ducplicidad de datos, nombre de producto
    $sql_verify = MainModel::executeQuerySimple("SELECT * FROM products WHERE product_id<>$product_id AND name='$name'");
    $products = $sql_verify->fetchAll();
    $duplicated = count($products) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El nombre de un producto único, no pueden repetirse.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $new_data = [
      "product_id" => $product_id,
      "link_image" => $link_image,
      "name" => $name,
      "inventary_min" => $min,
      "price_sale" => $price,
      "unit" => $unit,
      "sale_for" => $sale_for,
      "category_id" => $category,
      "is_active" => $is_active,
      "created_at" =>  date('Y-m-d H:i:s'),
    ];

    $stm = ProductModel::editProductModel($new_data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Producto actualizado",
        "text" => "El producto se actualizó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "El producto no se actualizó.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
  }

  // Funcion controlador para eliminar producto
  public function deleteProductController()
  {
    $product_id = $_POST['tx_product_id'];

    $stm = ProductModel::deleteProductModel($product_id);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Producto eliminado",
        "text" => "El producto se eliminó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Al parecer ocurrió  un error",
        "text" => "Producto no eliminado.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
}
