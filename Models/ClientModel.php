<?php

require_once "MainModel.php";

class ClientModel extends MainModel
{
  // Funcion de obtener clientes
  protected static function getClientsModel(string $words = ""): array
  {
    if (!empty($words)) {
      $words = "%$words%";
      $query = "SELECT * FROM persons WHERE (CONCAT(names, ' ', lastnames) LIKE :words OR dni LIKE :words OR RUC LIKE :words) AND kind=" . PERSON_TYPE->client;
      $clients = MainModel::connect()->prepare($query);
      $clients->bindParam(":words", $words);
    } else {
      $query = "SELECT * FROM persons WHERE kind=" . PERSON_TYPE->client;
      $clients = MainModel::connect()->prepare($query);
    }

    $clients->execute();

    return $clients->fetchAll();
  }

  // Funcion de obtener datos de un cliente mediamte su dni o RUC
  protected static function getDataClientModel(array $data): mixed
  {
    $column = ($data['typeProof'] == TYPE_PROOF->boleta) ? $column = "dni" : (($data['typeProof'] == TYPE_PROOF->factura) ? $column = "RUC" : "");
    $dni_ruc = $data['dni_ruc'];
    $query = "SELECT * FROM persons WHERE $column=:dni_ruc";
    $client = MainModel::connect()->prepare($query);
    $client->bindParam(":dni_ruc", $dni_ruc);


    $client->execute();

    return $client->fetch();
  }

  // Funcion para crear cliente 
  public static function createClientModel(array $data)
  {
    $type_client = PERSON_TYPE->client;

    if (!empty($data['RUC']) && !empty($data['dni'])) {
      $statement = MainModel::connect()->prepare("INSERT INTO 
      persons(person_id,dni,RUC, names, lastnames, address, phone, email, kind, created_at) 
      VALUES(:person_id,:dni,:RUC, :names, :lastnames, :address, :phone, :email, :kind, :created_at)");

      $statement->bindParam(":person_id", $data['person_id'], PDO::PARAM_STR);
      $statement->bindParam(":dni", $data['dni'], PDO::PARAM_INT);
      $statement->bindParam(":RUC", $data['RUC'], PDO::PARAM_INT);
      $statement->bindParam(":names", $data['names'], PDO::PARAM_STR);
      $statement->bindParam(":lastnames", $data['lastnames'], PDO::PARAM_STR);
      $statement->bindParam(":address", $data['address'], PDO::PARAM_STR);
      $statement->bindParam(":phone", $data['phone'], PDO::PARAM_STR);
      $statement->bindParam(":email", $data['email'], PDO::PARAM_STR);
      $statement->bindParam(":kind", $type_client, PDO::PARAM_BOOL);
      $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);
    }

    if (empty($data['RUC'])) {
      $statement = MainModel::connect()->prepare("INSERT INTO 
      persons(person_id,dni, names, lastnames, address, phone, email, kind, created_at) 
      VALUES(:person_id,:dni, :names, :lastnames, :address, :phone, :email, :kind, :created_at)");

      $statement->bindParam(":person_id", $data['person_id'], PDO::PARAM_STR);
      $statement->bindParam(":dni", $data['dni'], PDO::PARAM_INT);
      $statement->bindParam(":names", $data['names'], PDO::PARAM_STR);
      $statement->bindParam(":lastnames", $data['lastnames'], PDO::PARAM_STR);
      $statement->bindParam(":address", $data['address'], PDO::PARAM_STR);
      $statement->bindParam(":phone", $data['phone'], PDO::PARAM_STR);
      $statement->bindParam(":email", $data['email'], PDO::PARAM_STR);
      $statement->bindParam(":kind", $type_client, PDO::PARAM_BOOL);
      $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);
    }

    if (empty($data['dni'])) {
      $statement = MainModel::connect()->prepare("INSERT INTO 
      persons(person_id,RUC, names, lastnames, address, phone, email, kind, created_at) 
      VALUES(:person_id,:RUC, :names, :lastnames, :address, :phone, :email, :kind, :created_at)");

      $statement->bindParam(":person_id", $data['person_id'], PDO::PARAM_STR);
      $statement->bindParam(":RUC", $data['RUC'], PDO::PARAM_INT);
      $statement->bindParam(":names", $data['names'], PDO::PARAM_STR);
      $statement->bindParam(":lastnames", $data['lastnames'], PDO::PARAM_STR);
      $statement->bindParam(":address", $data['address'], PDO::PARAM_STR);
      $statement->bindParam(":phone", $data['phone'], PDO::PARAM_STR);
      $statement->bindParam(":email", $data['email'], PDO::PARAM_STR);
      $statement->bindParam(":kind", $type_client, PDO::PARAM_BOOL);
      $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);
    }



    return $statement->execute();
  }
}
