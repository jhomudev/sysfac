<?php

if ($requestFetch) {
  require_once "./../Models/SupplierModel.php";
} else {
  require_once "./Models/SupplierModel.php";
}

class SupplierController extends SupplierModel
{
  // Función controlador para obtener los proveedores
  public function getSuppliersController()
  {
    $suppliers = SupplierModel::getSuppliersModel();
    return json_encode($suppliers);
  }

  // Funcion controlador para obetenr los datos de un proveedor
  public function getDataSupplierController()
  {
    if (empty($_POST['supplierIdRUC'])) {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps...",
        "text" => "Proveedor no definido.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $supplier_id = intval($_POST['supplierIdRUC']);
    $supplier = SupplierModel::getDataSupplierModel($supplier_id);

    return json_encode($supplier);
  }

  // Funcion controlador para crear un proveedor
  public function createSupplierController()
  {
    $RUC = MainModel::clearString($_POST['tx_ruc']);
    $name = MainModel::clearString($_POST['tx_nombre']);
    $address = MainModel::clearString($_POST['tx_direccion']);
    $phone = MainModel::clearString($_POST['tx_telefono']);

    // Validacion de campos vacios
    if (empty($name) || empty($phone) || empty($address) || empty($RUC)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validación de duplicidad de nombre o RUC del proveedor
    $sql_verify = MainModel::executeQuerySimple("SELECT * FROM suppliers WHERE name='$name' OR RUC = $RUC");
    $suppliers = $sql_verify->fetchAll();
    $duplicated = count($suppliers) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El nombre y RUC de cada prveedor no pueden ser iguales.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $data = [
      "RUC" => $RUC,
      "name" => $name,
      "address" => $address,
      "phone" => $phone,
      "created_at" =>  date('Y-m-d H:i:s'),
    ];

    $stm = SupplierModel::createSupplierModel($data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Proveedor agregado",
        "text" => "El proveedor se agregó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "El proveedor no se agregó.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
  // Funcion controlador para crear o editar usuario
  public function editSupplierController()
  {
    $supplier_id = MainModel::clearString($_POST['tx_supplier_id']);
    $RUC = MainModel::clearString($_POST['tx_ruc']);
    $name = MainModel::clearString($_POST['tx_nombre']);
    $address = MainModel::clearString($_POST['tx_direccion']);
    $phone = MainModel::clearString($_POST['tx_telefono']);

    // Validacion de campos vacios
    if (empty($name) || empty($phone) || empty($address) || empty($RUC)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validación de duplicidad de nombre o RUC del proveedor
    $sql_verify = MainModel::executeQuerySimple("SELECT * FROM suppliers WHERE supplier_id<>$supplier_id AND (name='$name' OR RUC = $RUC)");
    $suppliers = $sql_verify->fetchAll();
    $duplicated = count($suppliers) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El nombre y RUC de cada prveedor no pueden ser iguales.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $data = [
      "supplier_id" => $supplier_id,
      "RUC" => $RUC,
      "name" => $name,
      "address" => $address,
      "phone" => $phone,
    ];

    $stm = SupplierModel::editSupplierModel($data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Proveedor modificado",
        "text" => "El proveedor se modificó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "El proveedor no se modificó.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }

  // Funcion controlador para eliminar usuario
  public function deleteSupplierController()
  {
    $supplier_id = intval($_POST['tx_supplier_id']);

    // Verificaion si supplier_id fue definido
    if (empty($supplier_id)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps...",
        "text" => "Proveedor no definido.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $stm = SupplierModel::deleteSupplierModel($supplier_id);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Proveedor eliminado",
        "text" => "El proveedor se eliminó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Al parecer ocurrió  un error",
        "text" => "Proveedor no eliminado.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
}
