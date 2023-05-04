<?php

require_once "MainModel.php";

class ClientModel extends MainModel
{
  // Funcion de obtener clientes
  protected static function getClientsModel(string $words = "")
  {
    if (isset($words)) {
      $query = "SELECT * FROM persons WHERE CONCAT(names, ' ', lastnames) LIKE :words AND kind=:kind";
      $products = MainModel::connect()->prepare($query);
      $products->bindParam(":kind", 1);
      $products->bindParam(":name", "%$words%");
    } else {
      $query = "SELECT * FROM persons WHERE kind=:kind";
      $products = MainModel::connect()->prepare($query);
      $products->bindParam(":kind", 1);
    }

    $products->execute();

    return $products;
  }

  // Funcion de obtener datos de un cliente mediamte su dni o RUC
  protected static function getDataClientModel(array $data)
  {
    if (isset($data['dni'])) {
      $dni = $data['dni'];
      $query = "SELECT * FROM persons WHERE dni=:dni";
      $client = MainModel::connect()->prepare($query);
      $client->bindParam(":dni", $dni);
    } else if (isset($data['RUC'])) {
      $RUC = $data['RUC'];
      $query = "SELECT * FROM persons WHERE RUC=:RUC";
      $client = MainModel::connect()->prepare($query);
      $client->bindParam(":RUC", $RUC);
    }

    $client->execute();

    return $client;
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
