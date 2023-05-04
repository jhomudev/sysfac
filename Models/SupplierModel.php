<?php

require_once "MainModel.php";

class SupplierModel extends MainModel
{

  // Funcion de obtener proveedores
  protected static function getSuppliersModel()
  {
    $suppliers = MainModel::executeQuerySimple("SELECT * FROM suppliers");

    return $suppliers;
  }

  // Funcion de obtener datos de un proveedor
  protected static function getDataSupplierModel(int $supplier_id)
  {
    $supplier = MainModel::connect()->prepare("SELECT * FROM suppliers WHERE supplier_id = :supplier_id");
    $supplier->bindParam("Supplier_id", $supplier_id);

    return $supplier;
  }

  // Funcion para crear proveedor 
  protected static function createSupplierModel(array $data)
  {
    $statement = MainModel::connect()->prepare("INSERT INTO 
    suppliers(RUC,name,address,phone,created_at) 
    VALUES(:RUC,:name,:address,:phone,:created_at)");

    $statement->bindParam(":RUC", $data['RUC']);
    $statement->bindParam(":name", $data['name']);
    $statement->bindParam(":address", $data['address']);
    $statement->bindParam(":phone", $data['phone']);
    $statement->bindParam(":created_at", $data['created_at']);

    $statement->execute();

    return $statement;
  }

  // Funciòn eliminar proveedor
  protected static function deleteSupplierModel(int $supplier_id)
  {
    $statement = MainModel::connect()->prepare("DELETE FROM suppliers WHERE supplier_id=:supplier_id");
    $statement->bindParam(":supplier_id", $supplier_id);
    $statement->execute();

    return $statement;
  }

  // Funcion para editar proveedor  
  protected static function editSupplierModel(array $new_data)
  {
    $statement = MainModel::connect()->prepare("UPDATE suppliers SET RUC=:RUC,name=:name,address=:address,phone=:phone,created_at=:created_at WHERE supplier_id=:supplier_id");

    $statement->bindParam(":supplier_id", $new_data['supplier_id']);
    $statement->bindParam(":RUC", $new_data['RUC']);
    $statement->bindParam(":name", $new_data['name']);
    $statement->bindParam(":address", $new_data['address']);
    $statement->bindParam(":phone", $new_data['phone']);
    $statement->bindParam(":created_at", $new_data['created_at']);

    $statement->execute();

    return $statement;
  }
}
