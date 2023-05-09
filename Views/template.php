<?php
$requestFetch = false;
require_once "./Controllers/ViewController.php";
$IV = new ViewController();
$vista = $IV->getViewController();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="shortcut icon" href="<?php echo SERVER_URL ?>/Views/assets/iconLogo.png" type="image/x-icon">
  <?php
  if ($vista === "login") echo '<link rel="stylesheet" href="' . SERVER_URL . '/Views/css/login.css">';
  else if ($vista !== "login" || $vista !== "404") echo '<link rel="stylesheet" href="' . SERVER_URL . '/Views/css/panel.css">';
  ?>
  <?php  ?>
  <title><?php echo COMPANY ?></title>
</head>

<body>
  <?php
  if ($vista == "login" || $vista == "404") {
    require_once "./Views/contents/$vista-view.php";
  } else {
    session_name(NAMESESSION);
    session_start();

    require_once "Controllers/LoginController.php";
    $lc = new LoginController();

    if (!isset($_SESSION["token"])) {
      $lc->forceLogoutController();
      exit();
    }
  ?>
    <div class="container_all">
      <?php include "./views/inc/menuBar.php"; ?>
      <div class="col_2">
        <?php include "./views/inc/header.php"; ?>
        <main class="view">
          <?php
          // Condicionales para las viostas dependiendo del tipo de usuario
          if (($_SESSION['type'] == USER_TYPE->vendedor) && ($vista == "ventas" || $vista == "clientes" || $vista == "dashboard")) include  "./Views/contents/" . $vista . "-view.php";
          else if ($_SESSION['type'] == USER_TYPE->superadmin || $_SESSION['type'] == USER_TYPE->admin) include  "./Views/contents/" . $vista . "-view.php";
          else echo "No tiene acceso a este módulo";
          ?>
        </main>
      </div>
    </div>
    <script src="<?php echo SERVER_URL; ?>/Views/js/main.js"></script>
    <script src="<?php echo SERVER_URL; ?>/Views/js/alerts.js"></script>
    <script src="<?php echo SERVER_URL; ?>/Views/js/<?php echo $vista; ?>.js"></script>
  <?php
    include "./Views/inc/logout.php";
    include "./Views/inc/scriptMenuBar.php";
  } ?>
</body>