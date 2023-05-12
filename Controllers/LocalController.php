<?php

if ($requestFetch) {
  require_once "./../Models/LocalModel.php";
} else {
  require_once "./Models/LocalModel.php";
}

class LocalController extends LocalModel
{
  // Función controlador para obtener los usuarios
  public function getLocalsController()
  {
    $locations = LocalModel::getLocalsModel();
    return json_encode($locations);
  }

  // Funcion controlador para obetenr los datos de usuario
  public function getDataLocalController()
  {
    $location_id = intval($_POST['localId']);
    $location = LocalModel::getDataLocalModel($location_id);
    return json_encode($location);
  }

  // Funcion controlador para crear o editar usuario
  public function createLocalController()
  {
    $name = MainModel::clearString($_POST['tx_nombre']);
    $address = MainModel::clearString($_POST['tx_direccion']);
    $type = MainModel::clearString($_POST['tx_type']);
    $canStoreMore = MainModel::clearString($_POST['tx_canStore']);

    // Validacion de campos vacios
    if (empty($name) || empty($address) || empty($type)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validación de duplicidad de nombre
    $sql_verify = MainModel::executeQuerySimple("SELECT * FROM locations WHERE name='$name'");
    $locations = $sql_verify->fetchAll();
    $duplicated = count($locations) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El nombre de cada local debe ser único.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $data = [
      "name" => $name,
      "address" => $address,
      "type" => $type,
      "canStoreMore" => intval($canStoreMore),
      "created_at" =>  date('Y-m-d H:i:s'),
    ];

    $stm = LocalModel::createLocalModel($data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Local agregado",
        "text" => "El local se agregó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "El local no se agregó.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
  // Funcion controlador para crear o editar usuario
  public function editLocalController()
  {
    $local_id = MainModel::clearString($_POST['tx_local_id']);
    $name = MainModel::clearString($_POST['tx_nombre']);
    $address = MainModel::clearString($_POST['tx_direccion']);
    $type = MainModel::clearString($_POST['tx_type']);
    $canStoreMore = MainModel::clearString($_POST['tx_canStore']);

    // Validacion de campos vacios
    if (empty($name) || empty($address) || empty($type)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validación de duplicidad de nombre
    $sql_verify = MainModel::executeQuerySimple("SELECT * FROM locations WHERE local_id<>$local_id AND name='$name'");
    $locations = $sql_verify->fetchAll();
    $duplicated = count($locations) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El nombre de cada local debe ser único.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $new_data = [
      "local_id" => $local_id,
      "name" => $name,
      "address" => $address,
      "type" => $type,
      "canStoreMore" => $canStoreMore,
    ];

    $stm = LocalModel::editlocalModel($new_data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Local modificado",
        "text" => "El local se modificó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "El local no se modificó.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }

  // Funcion controlador para eliminar usuario
  public function deleteLocalController()
  {
    $local_id = intval($_POST['tx_local_id']);

    // Verificacion si hay unidades de productos en el local
    $query_verify = MainModel::executeQuerySimple("SELECT * FROM products_all WHERE local_id=$local_id");
    $products = $query_verify->fetchAll();
    $exist_products = count($products) > 0;

    if ($exist_products) {
      $alert = [
        "Alert" => "simple",
        "title" => "Acción rechazada",
        "text" => "Hay productos que figuran en este local, por lo cual no puede eliminarlo.",
        "icon" => "warning"
      ];

      return json_encode($alert);
      exit();
    }

    $stm = LocalModel::deleteLocalModel($local_id);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Local eliminado",
        "text" => "El local se eliminó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Al parecer ocurrió  un error",
        "text" => "Local no eliminado.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
}
