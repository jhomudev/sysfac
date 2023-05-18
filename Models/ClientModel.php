<?php

require_once "MainModel.php";

class ClientModel extends MainModel
{
  // Funcion de obtener clientes
  protected static function getClientsModel(string $words = ""): array
  {
    if (!empty($words)) {
      $words = "%$words%";
      $query = "SELECT * FROM persons WHERE CONCAT(names, ' ', lastnames) LIKE :words AND kind=" . PERSON_TYPE->client;
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
  protected static function getDataClientModel(array $data):mixed
  {

    $column = $data['tx_search'];
    $dni_ruc = $data['tx_dni_ruc'];
    $query = "SELECT * FROM persons WHERE $column=:dni_ruc";
    $client = MainModel::connect()->prepare($query);
    $client->bindParam(":dni_ruc", $dni_ruc);


    $client->execute();

    return $client->fetch();
  }

  // Funcion para crear usuarios 
  public static function createClientModel(array $data)
  {
    $statement = MainModel::connect()->prepare("INSERT INTO 
    persons(dni,names,lastnames,address,phone,email,kind,created_at) 
    VALUES(:dni,:names,:lastnames,:address,:phone,:email,:kind,:created_at)");

    $statement->bindParam(":dni", $data['dni']);
    $statement->bindParam(":names", $data['names']);
    $statement->bindParam(":lastnames", $data['lastnames']);
    $statement->bindParam(":address", $data['address']);
    $statement->bindParam(":phone", $data['phone']);
    $statement->bindParam(":email", $data['email']);
    $statement->bindParam(":kind", 1);
    $statement->bindParam(":created_at", $data['created_at']);
    $statement->execute();

    return $statement;
  }
}
