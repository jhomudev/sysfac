<?php
$requestFetch = false;
require_once "./Controllers/viewController.php";
$IV = new viewController();
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

    require_once "Controllers/logincontroller.php";
    $lc = new loginController();

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
          <!-- Aqui va la vista  seleccionada en el menú-->
          <?php include  "./Views/contents/" . $vista . "-view.php"; ?>
        </main>
      </div>
    </div>
    <script src="<?php echo SERVER_URL; ?>/Views/js/main.js"></script>
    <script src="<?php echo SERVER_URL; ?>/Views/js/alerts.js"></script>
  <?php
    include "./Views/inc/logout.php";
    include "./Views/inc/scriptMenuBar.php";
  } ?>
</body>