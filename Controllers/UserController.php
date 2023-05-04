<?php

if ($requestFetch) {
  require_once "./../Models/UserModel.php";
} else {
  require_once "./Models/UserModel.php";
}

class UserController extends UserModel
{
  // Función controlador para obtener los usuarios
  public function getUsersController()
  {
    $users = UserModel::getUsersModel();
    return json_encode($users->fetchAll());
  }

  // Funcion controlador para obetenr los datos de usuario
  public function getDataUserController()
  {
    $user_id = intval($_POST['userId']);
    $user = UserModel::getDataUserModel($user_id);
    return json_encode($user->fetch());
  }

  // Funcion controlador para crear o editar usuario
  public function createOrEditUserController()
  {
    try {
      $user_id = mainModel::clearString($_POST['tx_user_id']);
      $dni = mainModel::clearString($_POST['tx_dni']);
      $names = mainModel::clearString($_POST['tx_nombres']);
      $lastnames = mainModel::clearString($_POST['tx_apellidos']);
      $username = mainModel::clearString($_POST['tx_username']);
      $password = mainModel::clearString($_POST['tx_password']);
      $email = mainModel::clearString($_POST['tx_correo']);
      $is_admin = intval($_POST['tx_acceso']);
      $is_active = intval($_POST['tx_activo']);

      if (empty($dni) || empty($names) || empty($lastnames) || empty($username) || empty($password) || empty($email)) {
        $alert = [
          "Alert" => "simple",
          "title" => "Campos vacios",
          "text" => "Por favor. Complete todos los campos.",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }

      if (empty($user_id)) {
        $data = [
          "dni" => $dni,
          "names" => $names,
          "lastnames" => $lastnames,
          "username" => $username,
          "password" => $password,
          "email" => $email,
          "is_active" => $is_active,
          "is_admin" => $is_admin,
          "created_at" =>  date('Y-m-d H:i:s'),
        ];

        $stm = UserModel::createUserModel($data);

        if ($stm) {
          $alert = [
            "Alert" => "simple",
            "title" => "Usuario creado",
            "text" => "El usuario se creó exitosamente.",
            "icon" => "success"
          ];
          return json_encode($alert);
        } else {
          $alert = [
            "Alert" => "simple",
            "title" => "Opps. Ocurrió un problema",
            "text" => "El usuario no se creó.",
            "icon" => "error"
          ];
          return json_encode($alert);
        }
      } else {
        $new_data = [
          "user_id" => $user_id,
          "dni" => $dni,
          "names" => $names,
          "lastnames" => $lastnames,
          "username" => $username,
          "password" => $password,
          "email" => $email,
          "is_active" => $is_active,
          "is_admin" => $is_admin,
        ];

        $stm = UserModel::editUserModel($new_data);

        if ($stm) {
          $alert = [
            "Alert" => "simple",
            "title" => "Usuario actualizado",
            "text" => "El usuario se actualizó exitosamente.",
            "icon" => "success"
          ];
          return json_encode($alert);
        } else {
          $alert = [
            "Alert" => "simple",
            "title" => "Opps. Ocurrió un problema",
            "text" => "El usuario no se actualizó.",
            "icon" => "error"
          ];
          return json_encode($alert);
        }
      }
    } catch (PDOException $e) {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Al parecer ocurrió un error.",
        "text" => "Error: " . $e->getMessage(),
        "icon" => "error"
      ];
      return json_encode($alert);
    }
  }

  // Funcion controlador para eliminar usuario
  public function deleteUserController()
  {
    $user_id = $_POST['tx_user_id'];

    if ($user_id == 1) {
      $alert = [
        "Alert" => "simple",
        "title" => "Acción rechazada",
        "text" => "El usuario principal no puede ser eliminado.",
        "icon" => "warning"
      ];

      return json_encode($alert);
      exit();
    }

    $stm = UserModel::deleteUserModel($user_id);

    if ($stm) {
      $alert = [
        "Alert" => "simple",
        "title" => "Usuario eliminado",
        "text" => "El usuario se eliminó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Al parecer ocurrió  un error",
        "text" => "Usuario no eliminado.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
  }
}
