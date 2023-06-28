<?php

if ($request) {
  require_once "./../Models/LoginModel.php";
} else {
  require_once "./Models/LoginModel.php";
}

class LoginController extends LoginModel
{
  // Función controlador para inicio de sesión
  public function LoginController()
  {
    $username = MainModel::getCleanPostValue('tx_username');
    $password = MainModel::getCleanPostValue('tx_password');

    // Comprabar campos vacios
    if ($username == "" || $password == "") {
      echo '
      <script>
        Swal.fire({
          icon: "error",
          title: "Campos vacios",
          text: "Por favor. complete todos los campos",
          confirmButtonText: "Aceptar",
        });
      </script>
      ';

      exit();
    }

    $data_login = [
      "username" => $username,
      "password" => $this->encryption($password),
    ];

    $data_user = LoginModel::LoginModel($data_login);

    if ($data_user->rowCount() > 0) {
      $data_user = $data_user->fetch();

      // Verificacion si esta activo o no
      if ($data_user['is_active'] == STATE->inactive) {
        echo '
        <script>
          Swal.fire({
            icon: "error",
            title: "Usuario inactivo",
            text: "Su usuario no tiene acceso al sistema, ya que está inactivo.",
            confirmButtonText: "Aceptar",
          });
        </script>
        ';
        exit();
      }

      $data_user['token'] = md5(uniqid(mt_rand(), true));

      session_name(NAMESESSION);
      session_start();
      $_SESSION = $data_user;

      header("Location: " . SERVER_URL . "/dashboard");
    } else {
      echo '
      <script>
        Swal.fire({
          icon: "error",
          title: "Acceso denegado",
          text: "El usuario y/o contraseña son incorrectos",
          confirmButtonText: "Aceptar",
        });
      </script>
      ';
    }
  }

  // Funcion controlador para forzar cierre de sesión si no esta logeado
  public function forceLogoutController()
  {
    session_name(NAMESESSION);
    session_unset();
    session_destroy();

    if (headers_sent()) echo '<script> window.location.href=' . SERVER_URL . '/login </script>';
    else header("Location: " . SERVER_URL . "/login");
  }

  // Funcion controlador para forzar inicio de sesión si ya esta logeado
  public function forceLogin()
  {
    session_name(NAMESESSION);
    session_start();
    if (isset($_SESSION["token"])) header("Location: " . SERVER_URL . "/dashboard");
  }

  // Funcion controlador para cerrar sesión
  public function logoutController()
  {
    session_unset();
    session_destroy();

    $alert = [
      "Alert" => "reload"
    ];

    echo json_encode($alert);
  }
}
