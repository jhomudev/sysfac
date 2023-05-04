<?php

require_once "MainModel.php";

class LoginModel extends MainModel
{

  // Función para inicio de sesión
  protected static function LoginModel(array $data)
  {
    $sql = MainModel::connect()->prepare("SELECT * FROM users WHERE username = :username AND password = :password AND is_active=1");
    $sql->bindParam(':username', $data['username']);
    $sql->bindParam(':password', $data['password']);
    $sql->execute();

    return $sql;
  }
}
