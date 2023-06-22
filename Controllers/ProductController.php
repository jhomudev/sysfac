<?php

if ($request) {
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
      "words" => MainModel::getCleanPostValue('words'),
      "category_id" => MainModel::getCleanPostValue('category_id'),
      "is_active" => MainModel::getCleanPostValue('is_active'),
    ];

    $products = ProductModel::getProductsModel($filters);

    // Agregando stock a los productos
    foreach ($products as $key => $product) {
      $stock = MainModel::executeQuerySimple("SELECT COUNT(pa.product_id) FROM products_all pa INNER JOIN products p ON p.product_id=pa.product_id WHERE pa.product_id=" . $products[$key]['product_id'] . " AND pa.state=" . STATE_IN->stock)->fetchColumn();

      $products[$key]['stock'] = $stock;
    }

    // obtencion de un nuevo array sin version indexada
    $arr_new_products = [];
    foreach ($products as $product) {
      $filteredElement = [];

      foreach ($product as $key => $value) {
        if (is_string($key)) {
          $filteredElement[$key] = $value;
        }
      }

      $arr_new_products[] = $filteredElement;
    }
    // conversion de file_image a base64 para poder ser leida por json_encode()
    foreach ($arr_new_products as $key => $product) {
      if (!empty($arr_new_products[$key]['file_image'])) {
        $arr_new_products[$key]['file_image'] = "data:image/png;base64," . base64_encode($arr_new_products[$key]['file_image']);
      }
    }

    // ordenamiento segun stock
    usort($arr_new_products, function ($a, $b) {
      return $a["stock"] - $b["stock"];
    });


    // return json_encode($arr_new_products);
    return json_encode($arr_new_products);
  }

  // Funcion controlador para obetenr los datos de producto
  public function getDataProductController()
  {
    $product_id_name = $_POST['productIdName'];
    $product = ProductModel::getDataProductModel($product_id_name);

    // obtencion de un nuevo array sin version indexada
    $arr_new_product = [];

    foreach ($product as $key => $value) {
      if (is_string($key)) {
        $arr_new_product[$key] = $value;
      }
    }
    if (!empty($arr_new_product['file_image'])) $arr_new_product['file_image'] = "data:image/png;base64," . base64_encode($arr_new_product['file_image']);

    return json_encode($arr_new_product);
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

    // valor de file_image
    if (isset($_FILES['file_cat']) && !empty($_FILES['file_cat']['tmp_name'])) {
      $file_image = file_get_contents($_FILES['file_cat']['tmp_name']);
      $link_image = "";
    } else  $file_image = "";

    // Validacion de campos vacios
    if (empty($price) || empty($name) || empty($unit) || empty($min) || empty($category) || !isset($is_active) || !isset($sale_for)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos necesarios.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validación datos duplicados, nombre de producto
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
      "file_image" => $file_image,
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
    // valor de file_image
    if (isset($_FILES['file_cat']) && !empty($_FILES['file_cat']['tmp_name'])) {
      $file_image = file_get_contents($_FILES['file_cat']['tmp_name']);
      $link_image = "";
    } else  $file_image = "";

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

    if (empty($file_image)) {
      // Obtencion de file_image de la categoria para validacion de imagen
      $sql_verify = MainModel::executeQuerySimple("SELECT file_image FROM products WHERE product_id=$product_id");
      $product_file_image = $sql_verify->fetchColumn();
      if ($product_file_image) $file_image = $product_file_image;
      if (!empty($link_image)) $file_image = "";
    }

    // validación de duplicidad de datos, nombre de producto
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
      "file_image" => $file_image,
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

    // Validacion de que el producto no tenga ventas registradas
    $ops = MainModel::executeQuerySimple("SELECT op.product_id FROM operations op INNER JOIN products p ON p.product_id=op.product_id WHERE op.product_id=$product_id");
    $ops = $ops->fetchAll();

    if (count($ops) > 0) {
      $alert = [
        "Alert" => "simple",
        "title" => "Acción rechazada",
        "text" => "No puede eliminar el producto, ya que este ya tiene venta y/o compras registradas.",
        "icon" => "warning"
      ];

      return json_encode($alert);
      exit();
    }

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

  // FUNCIONES CONTROLLER PARA PRODUCTS_ALL

  // Función controlador para obtener todos los productos en inventario
  public function getProductsInventaryController()
  {
    $filters = [
      "words" => isset($_POST['words_in']) && !empty($_POST['words_in']) ? MainModel::clearString($_POST['words_in']) : "",
      "product_id" => isset($_POST['product_id']) && !empty($_POST['product_id']) ? MainModel::clearString($_POST['product_id']) : "",
      "local_id" => isset($_POST['local_id']) && !empty($_POST['local_id']) ? MainModel::clearString($_POST['local_id']) : "",
      "state" => isset($_POST['state']) && !empty($_POST['state']) ? MainModel::clearString($_POST['state']) : "",
    ];

    $products_all = ProductModel::getProductsInventaryModel($filters);

    foreach ($products_all as $key => $product_unit) {
      if ($products_all[$key]['state'] == STATE_IN->stock) $products_all[$key]['state'] = 'En stock';
      if ($products_all[$key]['state'] == STATE_IN->damaged) $products_all[$key]['state'] = 'Dañado';
      if ($products_all[$key]['state'] == STATE_IN->sold) $products_all[$key]['state'] = 'Vendido';

      if (!empty($products_all[$key]['local_id'])) {
        $products_all[$key]['local_name'] = MainModel::executeQuerySimple("SELECT name FROM locations WHERE local_id=" . $products_all[$key]['local_id'])->fetchColumn();
      }
    }

    return json_encode($products_all);
  }

  // Funcion controlador para crear o editar producto
  public function editProductInventaryController()
  {
    $prods = isset($_POST['prods']) && !empty($_POST['prods']) ? MainModel::clearString($_POST['prods']) : "";
    $action = isset($_POST['action']) && !empty($_POST['action']) ? MainModel::clearString($_POST['action']) : "";
    $local_id = isset($_POST['local']) && !empty($_POST['local']) ? MainModel::clearString($_POST['local']) : "";
    $state = isset($_POST['state']) && !empty($_POST['state']) ? MainModel::clearString($_POST['state']) : "";

    // Validación de campos vacios en operacion
    if (empty($action) && ($action != "assign_local" || $action != "change_state")) {
      $alert = [
        "Alert" => "simple",
        "title" => "Operación no definida",
        "text" => "Por favor. Elija la operación que desea realizar.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }


    if (empty($prods)) {
      // Valididación de campos vacios en operacion
      $alert = [
        "Alert" => "simple",
        "title" => "Productos no seleccionados",
        "text" => "Seleccione los productos a actualizar.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $prods = explode(',', str_replace(" ", "", $prods));
    // validación de product_unit id existe en la Db
    $arr_inexist = [];
    foreach ($prods as $prod) {
      $sql_verify = MainModel::executeQuerySimple("SELECT * FROM products_all WHERE product_unit_id=" . intval($prod));
      $product = $sql_verify->fetch();
      if ($product == false) array_push($arr_inexist, $product);
    }

    if (count($arr_inexist) > 0) {
      $alert = [
        "Alert" => "simple",
        "title" => "Producto(s) inexistente(s)",
        "text" => "Algunos de los productos no existe.",
        "icon" => "warning"
      ];
      $alert = $arr_inexist;
      return json_encode($alert);
      exit();
    }

    if ($action == "assign_local") {
      if (empty($local_id)) {
        $alert = [
          "Alert" => "simple",
          "title" => "Local no definido",
          "text" => "Seleccione el local a asignar.",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }

      foreach ($prods as $prod) {
        $new_data = [
          "product_unit_id" => $prod,
          "local_id" => $local_id,
        ];
        $stm = ProductModel::editProductInventaryModel($new_data);
      }
    }

    if ($action == "change_state") {
      if (empty($state)) {
        $alert = [
          "Alert" => "simple",
          "title" => "Estado no definido",
          "text" => "Seleccione el estado a asignar.",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }

      foreach ($prods as $prod) {
        $new_data = [
          "product_unit_id" => $prod,
          "state" => $state,
        ];

        $stm = ProductModel::editProductInventaryModel($new_data);
      }
    }

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Acción realizada",
        "text" => "La operación se realizó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "No pudimos realizar la operación.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
  }
}
