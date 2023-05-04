<?php

require_once "MainModel.php";

class WareModel extends MainModel
{
  // Funcion de obtener almacenes
  protected static function getWaresModel()
  {
    $warehouses = MainModel::executeQuerySimple("SELECT * FROM warehouses");

    return $warehouses;
  }

  // Funcion de obtener datos de un almacen
  protected static function getDataWareModel(int $ware_id)
  {
    $warehouse = MainModel::connect()->prepare("SELECT * FROM warehouses WHERE ware_id = :ware_id");
    $warehouse->bindParam(":ware_id", $ware_id);

    return $warehouse;
  }

  // Funcion para crear almacen 
  protected static function createWareModel(array $data)
  {
    $statement = MainModel::connect()->prepare("INSERT INTO 
    warehouses(name,address,canStoreMore,created_at) 
    VALUES(:name,:address,:canStoreMore,:created_at)");

    $statement->bindParam(":name", $data['name']);
    $statement->bindParam(":address", $data['address']);
    $statement->bindParam(":canStoreMore", $data['canStoreMore']);
    $statement->bindParam(":created_at", $data['created_at']);

    $statement->execute();

    return $statement;
  }

  // Funciòn eliminar almacen
  protected static function deleteWareModel(int $ware_id)
  {
    $statement = MainModel::connect()->prepare("DELETE FROM warehouses WHERE ware_id=:ware_id");
    $statement->bindParam(":ware_id", $ware_id);
    $statement->execute();

    return $statement;
  }

  // Funcion para editar almacen  
  protected static function editWareModel(array $new_data)
  {
    $statement = MainModel::connect()->prepare("UPDATE warehouses SET name=:name,address=:address,canStoreMore=:canStoreMore,created_at=:created_at WHERE ware_id=:ware_id");

    $statement->bindParam(":ware_id", $new_data['ware_id']);
    $statement->bindParam(":name", $new_data['name']);
    $statement->bindParam(":address", $new_data['address']);
    $statement->bindParam(":canStoreMore", $new_data['canStoreMore']);
    $statement->bindParam(":created_at", $new_data['created_at']);

    $statement->execute();

    return $statement;
  }
}
