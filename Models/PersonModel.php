<?php

require_once "MainModel.php";

class PersonModel extends MainModel
{
  // Funcion de obtener persona suppliers
  protected static function getPersonsModel(): array
  {

    $query = "SELECT * FROM persons WHERE kind=" . PERSON_TYPE->supplier;
    $persons = MainModel::connect()->prepare($query);

    $persons->execute();

    return $persons->fetchAll();
  }

  // Funcion de obtener datos de un persona supplier mediamte su dni 
  protected static function getDataPersonModel(int $dni): mixed
  {
    $query = "SELECT * FROM persons WHERE dni=:dni";
    $person = MainModel::connect()->prepare($query);
    $person->bindParam(":dni", $dni);


    $person->execute();

    return $person->fetch();
  }

  // Funcion para crear persona supplier 
  public static function createPersonModel(array $data)
  {
    $type_person = PERSON_TYPE->supplier;

    $statement = MainModel::connect()->prepare("INSERT INTO 
    persons(person_id,dni, names, lastnames, supplier_id, address, phone, email, kind, created_at) 
    VALUES(:person_id,:dni, :names, :lastnames, :supplier_id, :address, :phone, :email, :kind, :created_at)");

    $statement->bindParam(":person_id", $data['person_id'], PDO::PARAM_STR);
    $statement->bindParam(":dni", $data['dni'], PDO::PARAM_INT);
    $statement->bindParam(":names", $data['names'], PDO::PARAM_STR);
    $statement->bindParam(":lastnames", $data['lastnames'], PDO::PARAM_STR);
    $statement->bindParam(":supplier_id", $data['supplier_id'], PDO::PARAM_INT);
    $statement->bindParam(":address", $data['address'], PDO::PARAM_STR);
    $statement->bindParam(":phone", $data['phone'], PDO::PARAM_STR);
    $statement->bindParam(":email", $data['email'], PDO::PARAM_STR);
    $statement->bindParam(":kind", $type_person, PDO::PARAM_BOOL);
    $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);

    return $statement->execute();
  }
}
