<?php

require_once "mainModel.php";

class loginModel extends mainModel
{

  // Función para inicio de sesión
  protected static function loginModel(array $data)
  {
    $sql = mainModel::connect()->prepare("SELECT * FROM users WHERE username = :username AND password = :password AND is_active=1");
    $sql->bindParam(':username', $data['username']);
    $sql->bindParam(':password', $data['password']);
    $sql->execute();

    return $sql;
  }
}
