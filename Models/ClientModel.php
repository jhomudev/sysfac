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
  protected static function getDataClientModel(array $data): mixed
  {
    if (isset($data['client_id']) && !empty($data['client_id'])) $column = "client_id";
    else $column = "dni";
    
    $id_dni_ruc = $data['id_dni_ruc'];

    $client = MainModel::connect()->prepare("SELECT * FROM clients WHERE $column=:id_dni_ruc");
    $client->bindParam(":id_dni_ruc", $id_dni_ruc);


    $client->execute();

    return $client->fetch();
  }

  // Funcion para crear cliente 
  public static function createClientModel(array $data)
  {
    if (!empty($data['RUC']) && !empty($data['dni'])) {
      $statement = MainModel::connect()->prepare("INSERT INTO 
      clients(client_id,dni,RUC, names, lastnames, address, phone, email, created_at) 
      VALUES(:client_id,:dni,:RUC, :names, :lastnames, :address, :phone, :email, :created_at)");

      $statement->bindParam(":client_id", $data['client_id'], PDO::PARAM_STR);
      $statement->bindParam(":dni", $data['dni'], PDO::PARAM_INT);
      $statement->bindParam(":RUC", $data['RUC'], PDO::PARAM_INT);
      $statement->bindParam(":names", $data['names'], PDO::PARAM_STR);
      $statement->bindParam(":lastnames", $data['lastnames'], PDO::PARAM_STR);
      $statement->bindParam(":address", $data['address'], PDO::PARAM_STR);
      $statement->bindParam(":phone", $data['phone'], PDO::PARAM_STR);
      $statement->bindParam(":email", $data['email'], PDO::PARAM_STR);
      $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);
    }

    if (empty($data['RUC'])) {
      $statement = MainModel::connect()->prepare("INSERT INTO 
      clients(client_id,dni, names, lastnames, address, phone, email, created_at) 
      VALUES(:client_id,:dni, :names, :lastnames, :address, :phone, :email, :created_at)");

      $statement->bindParam(":client_id", $data['client_id'], PDO::PARAM_STR);
      $statement->bindParam(":dni", $data['dni'], PDO::PARAM_INT);
      $statement->bindParam(":names", $data['names'], PDO::PARAM_STR);
      $statement->bindParam(":lastnames", $data['lastnames'], PDO::PARAM_STR);
      $statement->bindParam(":address", $data['address'], PDO::PARAM_STR);
      $statement->bindParam(":phone", $data['phone'], PDO::PARAM_STR);
      $statement->bindParam(":email", $data['email'], PDO::PARAM_STR);
      $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);
    }

    if (empty($data['dni'])) {
      $statement = MainModel::connect()->prepare("INSERT INTO 
      clients(client_id,RUC, names, lastnames, address, phone, email, created_at) 
      VALUES(:client_id,:RUC, :names, :lastnames, :address, :phone, :email, :created_at)");

      $statement->bindParam(":client_id", $data['client_id'], PDO::PARAM_STR);
      $statement->bindParam(":RUC", $data['RUC'], PDO::PARAM_INT);
      $statement->bindParam(":names", $data['names'], PDO::PARAM_STR);
      $statement->bindParam(":lastnames", $data['lastnames'], PDO::PARAM_STR);
      $statement->bindParam(":address", $data['address'], PDO::PARAM_STR);
      $statement->bindParam(":phone", $data['phone'], PDO::PARAM_STR);
      $statement->bindParam(":email", $data['email'], PDO::PARAM_STR);
      $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);
    }



    return $statement->execute();
  }

  // Funcion para editar cliente 
  public static function editClientModel(array $new_data)
  {
    $client_id = $new_data['client_id'];

    if (!empty($new_data['RUC']) && !empty($new_data['dni'])) {
      $statement = MainModel::connect()->prepare("UPDATE clients SET dni = :dni , RUC = :RUC , names = :names, lastnames = :lastnames, address = :address, phone = :phone, email = :email WHERE client_id=:client_id");

      $statement->bindParam(":client_id", $client_id, PDO::PARAM_STR);
      $statement->bindParam(":dni", $new_data['dni'], PDO::PARAM_INT);
      $statement->bindParam(":RUC", $new_data['RUC'], PDO::PARAM_INT);
      $statement->bindParam(":names", $new_data['names'], PDO::PARAM_STR);
      $statement->bindParam(":lastnames", $new_data['lastnames'], PDO::PARAM_STR);
      $statement->bindParam(":address", $new_data['address'], PDO::PARAM_STR);
      $statement->bindParam(":phone", $new_data['phone'], PDO::PARAM_STR);
      $statement->bindParam(":email", $new_data['email'], PDO::PARAM_STR);
    }

    if (empty($new_data['RUC'])) {
      $statement = MainModel::connect()->prepare("UPDATE clients SET dni = :dni , names = :names, lastnames = :lastnames, address = :address, phone = :phone, email = :email WHERE client_id=:client_id");

      $statement->bindParam(":client_id", $client_id, PDO::PARAM_STR);
      $statement->bindParam(":dni", $new_data['dni'], PDO::PARAM_INT);
      $statement->bindParam(":names", $new_data['names'], PDO::PARAM_STR);
      $statement->bindParam(":lastnames", $new_data['lastnames'], PDO::PARAM_STR);
      $statement->bindParam(":address", $new_data['address'], PDO::PARAM_STR);
      $statement->bindParam(":phone", $new_data['phone'], PDO::PARAM_STR);
      $statement->bindParam(":email", $new_data['email'], PDO::PARAM_STR);
    }

    if (empty($new_data['dni'])) {
      $statement = MainModel::connect()->prepare("UPDATE clients SET RUC = :RUC , names = :names, lastnames = :lastnames, address = :address, phone = :phone, email = :email WHERE client_id=:client_id");

      $statement->bindParam(":client_id", $client_id, PDO::PARAM_STR);
      $statement->bindParam(":RUC", $new_data['RUC'], PDO::PARAM_INT);
      $statement->bindParam(":names", $new_data['names'], PDO::PARAM_STR);
      $statement->bindParam(":lastnames", $new_data['lastnames'], PDO::PARAM_STR);
      $statement->bindParam(":address", $new_data['address'], PDO::PARAM_STR);
      $statement->bindParam(":phone", $new_data['phone'], PDO::PARAM_STR);
      $statement->bindParam(":email", $new_data['email'], PDO::PARAM_STR);
    }

    return $statement->execute();
  }
}
