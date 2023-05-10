<?php

require_once "MainModel.php";

class LocalModel extends MainModel
{
  // Funcion de obtener locals
  protected static function getLocalsModel(): array
  {
    $locations = MainModel::executeQuerySimple("SELECT * FROM locations ORDER BY local_id DESC");

    return $locations->fetchAll();
  }

  // Funcion de obtener datos de un local
  protected static function getDataLocalModel(int $local_id): array
  {
    $location = MainModel::connect()->prepare("SELECT * FROM locations WHERE local_id = :local_id");
    $location->bindParam(":local_id", $local_id, PDO::PARAM_INT);
    $location->execute();

    return $location->fetch();
  }

  // Funcion para crear local 
  protected static function createLocalModel(array $data): bool
  {
    $statement = MainModel::connect()->prepare("INSERT INTO 
    locations(name,address,type,canStoreMore,created_at) 
    VALUES(:name,:address,:type,:canStoreMore,:created_at)");

    $statement->bindParam(":name", $data['name'], PDO::PARAM_STR);
    $statement->bindParam(":address", $data['address'], PDO::PARAM_STR);
    $statement->bindParam(":type", $data['type'], PDO::PARAM_INT);
    $statement->bindParam(":canStoreMore", $data['canStoreMore'], PDO::PARAM_BOOL);
    $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);

    return $statement->execute();
  }

  // Funcion para editar local  
  protected static function editLocalModel(array $new_data): bool
  {
    $statement = MainModel::connect()->prepare("UPDATE locations SET name=:name, address=:address, type=:type, canStoreMore=:canStoreMore WHERE local_id=:local_id");

    $statement->bindParam(":local_id", $new_data['local_id'], PDO::PARAM_INT);
    $statement->bindParam(":name", $new_data['name'], PDO::PARAM_STR);
    $statement->bindParam(":address", $new_data['address'], PDO::PARAM_STR);
    $statement->bindParam(":type", $new_data['type'], PDO::PARAM_INT);
    $statement->bindParam(":canStoreMore", $new_data['canStoreMore'], PDO::PARAM_BOOL);

    return $statement->execute();
  }

  // Funciòn eliminar local
  protected static function deleteLocalModel(int $local_id): bool
  {
    $statement = MainModel::connect()->prepare("DELETE FROM locations WHERE local_id=:local_id");
    $statement->bindParam(":local_id", $local_id, PDO::PARAM_INT);

    return $statement->execute();
  }
}
