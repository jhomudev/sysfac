<?php

if ($request) {
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
    return json_encode($users);
  }

  // Funcion controlador para obetenr los datos de usuario
  public function getDataUserController()
  {
    $user_id = MainModel::getCleanPostValue('userId');
    $user = UserModel::getDataUserModel($user_id);
    return json_encode($user);
  }

  // Funcion controlador para crear usuario
  public function createUserController()
  {
    $dni = MainModel::getCleanPostValue('tx_dni');
    $names = MainModel::getCleanPostValue('tx_nombres');
    $lastnames = MainModel::getCleanPostValue('tx_apellidos');
    $username = MainModel::getCleanPostValue('tx_username');
    $password = MainModel::getCleanPostValue('tx_password');
    $email = MainModel::getCleanPostValue('tx_correo');
    $type = intval($_POST['tx_acceso']);
    $is_active = intval($_POST['tx_activo']);

    // Validacion de campos vacios
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

    // validación de dni, correo duplicados
    $sql_verify = MainModel::executeQuerySimple("SELECT * FROM users WHERE dni=$dni OR email='$email' OR username='$username'");
    $users = $sql_verify->fetchAll();
    $duplicated = count($users) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El DNI, el correo y el nombre de usuario son datos únicos por usuario, no pueden repetirse.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $data = [
      "dni" => $dni,
      "names" => $names,
      "lastnames" => $lastnames,
      "username" => $username,
      "password" => $this->encryption($password),
      "email" => $email,
      "is_active" => $is_active,
      "type" => $type,
      "created_at" =>  date('Y-m-d H:i:s'),
    ];

    $stm = UserModel::createUserModel($data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Usuario creado",
        "text" => "El usuario se creó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "El usuario no se creó.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
  // Funcion controlador para crear o editar usuario
  public function editUserController()
  {
    $user_id = MainModel::getCleanPostValue('tx_user_id');
    $dni = MainModel::getCleanPostValue('tx_dni');
    $names = MainModel::getCleanPostValue('tx_nombres');
    $lastnames = MainModel::getCleanPostValue('tx_apellidos');
    $username = MainModel::getCleanPostValue('tx_username');
    $password = MainModel::getCleanPostValue('tx_password');
    $email = MainModel::getCleanPostValue('tx_correo');
    $type = intval($_POST['tx_acceso']);
    $is_active = intval($_POST['tx_activo']);

    // Valididación de campos vacios
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

    // validación de dni, correo duplicados
    $sql_verify = MainModel::executeQuerySimple("SELECT * FROM users WHERE user_id<>$user_id AND (dni=$dni OR email='$email' OR username='$username')");
    $users = $sql_verify->fetchAll();
    $duplicated = count($users) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El DNI, el correo y el nombre de usuario son datos únicos por usuario, no pueden repetirse.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $new_data = [
      "user_id" => $user_id,
      "dni" => $dni,
      "names" => $names,
      "lastnames" => $lastnames,
      "username" => $username,
      "password" => $this->encryption($password),
      "email" => $email,
      "is_active" => $is_active,
      "type" => $type,
    ];

    $stm = UserModel::editUserModel($new_data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Usuario actualizado",
        "text" => "El usuario se actualizó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "El usuario no se actualizó.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
  }

  // Funcion controlador para eliminar usuario
  public function deleteUserController()
  {
    $user_id = MainModel::getCleanPostValue('tx_user_id');

    // Verificacion si usuario a eliminar es el q esta logeado
    $isLoged = $user_id == $_SESSION['user_id'];

    if ($isLoged) {
      $alert = [
        "Alert" => "simple",
        "title" => "Acción rechazada",
        "text" => "Usted esta logeado.No puede eliminarse a si mismo.",
        "icon" => "warning"
      ];

      return json_encode($alert);
      exit();
    }

    // Verificacion si usuario a eliminar es superadmin
    $query_verify = MainModel::executeQuerySimple("SELECT * FROM users WHERE user_id=$user_id AND type=" . USER_TYPE->superadmin . "");
    $users = $query_verify->fetchAll();
    $isSuperAdmin = count($users) > 0;

    if ($isSuperAdmin) {
      $alert = [
        "Alert" => "simple",
        "title" => "No puede eliminar al usuario",
        "text" => "Los superadmins no pueden ser eliminados.",
        "icon" => "warning"
      ];

      return json_encode($alert);
      exit();
    }

    // Verificacion si usuario ya ha realizado movimiento
    $movs_verify = MainModel::executeQuerySimple("SELECT * FROM sells WHERE user_id=$user_id");
    $movements = $movs_verify->fetchAll();
    $has_movements = count($movements) > 0;

    if ($has_movements) {
      $alert = [
        "Alert" => "simple",
        "title" => "No puede eliminar al usuario",
        "text" => "Este usuario ya ha realizado movimientos dentro del sistema, por lo cual no puede eliminarlo.",
        "icon" => "warning"
      ];

      return json_encode($alert);
      exit();
    }

    $stm = UserModel::deleteUserModel($user_id);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
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
    exit();
  }
}
