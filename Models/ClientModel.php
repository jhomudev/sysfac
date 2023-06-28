<?php

require_once "MainModel.php";

class ClientModel extends MainModel
{
  // Funcion de obtener clientes
  protected static function getClientsModel(string $words = ""): array
  {
    if (!empty($words)) {
      $words = "%$words%";
      $query = "SELECT * FROM clients WHERE (CONCAT(names, ' ', lastnames) LIKE :words OR dni LIKE :words OR RUC LIKE :words) ORDER BY created_at DESC";
      $clients = MainModel::connect()->prepare($query);
      $clients->bindParam(":words", $words);
    } else {
      $query = "SELECT * FROM clients ORDER BY created_at DESC";
      $clients = MainModel::connect()->prepare($query);
    }

    $clients->execute();

    return $clients->fetchAll();
  }

  // Funcion de obtener datos de un cliente mediamte su dni o RUC
  protected static function getDataClientModel(string $client_id): mixed
  {
    $client = MainModel::connect()->prepare("SELECT * FROM clients WHERE client_id=:client_id");
    $client->bindParam(":client_id", $client_id);

    $client->execute();

    return $client->fetch();
  }

  // Funcion de obtener datos de un persona mediamte su dni o RUC con API
  protected static function getDataPersonByIdModel(string $id): mixed
  {
    $data = MainModel::consultDNIRUC($id);

    return $data;
  }

  // Funcion para crear cliente 
  protected static function createClientModel(array $data)
  {
    if (empty($data['RUC'])) {
      $statement = MainModel::connect()->prepare("INSERT IGNORE INTO 
      clients(client_id,dni, names, lastnames, created_at) 
      VALUES(:client_id,:dni, :names, :lastnames, :created_at)");

      $statement->bindParam(":client_id", $data['client_id'], PDO::PARAM_STR);
      $statement->bindParam(":dni", $data['dni'], PDO::PARAM_INT);
      $statement->bindParam(":names", $data['names'], PDO::PARAM_STR);
      $statement->bindParam(":lastnames", $data['lastnames'], PDO::PARAM_STR);
      $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);
    }

    if (empty($data['dni'])) {
      $statement = MainModel::connect()->prepare("INSERT IGNORE INTO 
      clients(client_id,RUC, names, lastnames, created_at) 
      VALUES(:client_id,:RUC, :names, :lastnames, :created_at)");

      $statement->bindParam(":client_id", $data['client_id'], PDO::PARAM_STR);
      $statement->bindParam(":RUC", $data['RUC'], PDO::PARAM_INT);
      $statement->bindParam(":names", $data['names'], PDO::PARAM_STR);
      $statement->bindParam(":lastnames", $data['lastnames'], PDO::PARAM_STR);
      $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);
    }

    return $statement->execute();
  }

  // Funcion para editar cliente 
  protected static function editClientModel(array $new_data)
  {
    $client_id = $new_data['client_id'];

    $statement = MainModel::connect()->prepare("UPDATE clients SET address = :address, phone = :phone, email = :email WHERE client_id=:client_id");

    $statement->bindParam(":client_id", $client_id, PDO::PARAM_STR);
    $statement->bindParam(":address", $new_data['address'], PDO::PARAM_STR);
    $statement->bindParam(":phone", $new_data['phone'], PDO::PARAM_STR);
    $statement->bindParam(":email", $new_data['email'], PDO::PARAM_STR);

    return $statement->execute();
  }
}
