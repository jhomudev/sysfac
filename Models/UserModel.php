<?php

require_once "MainModel.php";

class UserModel extends MainModel
{

  // Funcion de obtener usuarios
  protected static function getUsersModel()
  {
    $users = MainModel::executeQuerySimple("SELECT * FROM users");

    return $users;
  }

  // Funcion de obtener datos de un suario
  protected static function getDataUserModel(int $user_id)
  {
    $user = MainModel::connect()->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $user->bindParam(":user_id", $user_id);
    $user->execute();

    return $user;
  }

  // Funcion para crear usuarios 
  protected static function createUserModel(array $data)
  {
    $statement = MainModel::connect()->prepare("INSERT INTO 
    users(dni,names,lastnames,username,email,password,is_active,type,created_at) 
    VALUES(:dni,:names,:lastnames,:username,:email,:password,:is_active,:type,:created_at)");

    $statement->bindParam(":dni", $data['dni'], PDO::PARAM_INT);
    $statement->bindParam(":names", $data['names'], PDO::PARAM_STR);
    $statement->bindParam(":lastnames", $data['lastnames'], PDO::PARAM_STR);
    $statement->bindParam(":username", $data['username'], PDO::PARAM_STR);
    $statement->bindParam(":email", $data['email'], PDO::PARAM_STR);
    $statement->bindParam(":password", $data['password'], PDO::PARAM_STR);
    $statement->bindParam(":is_active", $data['is_active'], PDO::PARAM_BOOL);
    $statement->bindParam(":type", $data['type'], PDO::PARAM_INT);
    $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);

    return $statement->execute();
  }

  // Funciòn eliminar usuario
  protected static function deleteUserModel(int $user_id)
  {
    $statement = MainModel::connect()->prepare("DELETE FROM users WHERE user_id=:user_id");
    $statement->bindParam(":user_id", $user_id);

    return $statement->execute();
  }

  // Funcion para editar usuario  
  protected static function editUserModel(array $new_data)
  {
    $statement = MainModel::connect()->prepare("UPDATE users SET dni=:dni, names=:names, lastnames=:lastnames, username=:username, email=:email, password=:password, is_active=:is_active, type=:type WHERE user_id=:user_id");

    $statement->bindParam(":user_id", $new_data['user_id'], PDO::PARAM_INT);
    $statement->bindParam(":dni", $new_data['dni'], PDO::PARAM_INT);
    $statement->bindParam(":names", $new_data['names'], PDO::PARAM_STR);
    $statement->bindParam(":lastnames", $new_data['lastnames'], PDO::PARAM_STR);
    $statement->bindParam(":username", $new_data['username'], PDO::PARAM_STR);
    $statement->bindParam(":email", $new_data['email'], PDO::PARAM_STR);
    $statement->bindParam(":password", $new_data['password'], PDO::PARAM_STR);
    $statement->bindParam(":is_active", $new_data['is_active'], PDO::PARAM_BOOL);
    $statement->bindParam(":type", $new_data['type'], PDO::PARAM_INT);

    return $statement->execute();
  }
}
