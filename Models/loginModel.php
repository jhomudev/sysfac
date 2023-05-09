<?php

require_once "MainModel.php";

class LoginModel extends MainModel
{

  // Función para inicio de sesión
  protected static function LoginModel(array $data)
  {
    $sql = MainModel::connect()->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $sql->bindParam(':username', $data['username'], PDO::PARAM_STR);
    $sql->bindParam(':password', $data['password'], PDO::PARAM_STR);
    $sql->execute();

    return $sql;
  }
}
