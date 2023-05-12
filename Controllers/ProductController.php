<?php

if ($requestFetch) {
  require_once "./../Models/ProductModel.php";
} else {
  require_once "./Models/ProductModel.php";
}

class ProductController extends ProductModel
{
  // Función controlador para obtener los usuarios
  public function getProductsController()
  {
    $filters = [
      "words" => MainModel::clearString($_POST['words']),
      "column" => MainModel::clearString($_POST['column']),
      "value" => MainModel::clearString($_POST['value']),
    ];
    $product = ProductModel::getProductsModel($filters);

    return json_encode($product);
  }

  // Funcion controlador para obetenr los datos de usuario
  public function getDataProductController()
  {
    $product_id = intval($_POST['productId']);
    $product = ProductModel::getDataProductModel($product_id);
    return json_encode($product);
  }

  // Funcion controlador para crear o editar usuario
  public function createProductController()
  {
    $name = MainModel::clearString($_POST['tx_nombre']);
    $price = MainModel::clearString($_POST['tx_precio']);
    $unit = MainModel::clearString($_POST['tx_unidad']);
    $min = MainModel::clearString($_POST['tx_minimo']);
    $link_image = MainModel::clearString($_POST['tx_linkImage']);
    $category = MainModel::clearString($_POST['tx_category']);
    $is_active = intval($_POST['tx_activo']);

    // Validacion de campos vacios
    if (empty($price) || empty($name) || empty($unit) || empty($min) || empty($category) || !isset($is_active)) {
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
  // Funcion controlador para crear o editar usuario
  public function editProductController()
  {
    $product_id = MainModel::clearString($_POST['tx_product_id']);
    $name = MainModel::clearString($_POST['tx_nombre']);
    $price = MainModel::clearString($_POST['tx_precio']);
    $unit = MainModel::clearString($_POST['tx_unidad']);
    $min = MainModel::clearString($_POST['tx_minimo']);
    $link_image = MainModel::clearString($_POST['tx_linkImage']);
    $category = MainModel::clearString($_POST['tx_category']);
    $is_active = intval($_POST['tx_activo']);

    // Valididación de campos vacios
    if (empty($name) || empty($price) || empty($unit) || empty($min) || empty($category) || !isset($is_active)) {
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

  // Funcion controlador para eliminar usuario
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
