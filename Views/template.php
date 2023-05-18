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
      <?php include_once "./views/inc/menuBar.php"; ?>
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
      <div class="cart__modal">
        <h2 class="cart__title">Carrito de venta</h2>
        <div class="cart" id="cart">
          <div class="cart__tableBox">
            <table class="cart__table">
              <thead class="cart__table__thead">
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
                <th></th>
              </thead>
              <tbody class="cart__table__tbody" id="cartTableItems">
                <!-- peticion -->
              </tbody>
            </table>
          </div>
          <div class="cart__details">
            <table class="cart__details_table cart__table">
              <thead class="cart__table__thead">
                <tr>
                  <th>Importe</th>
                  <th>Descuento</th>
                  <th>IGV</th>
                  <th>Importe total</th>
                </tr>
              </thead>
              <tbody class="cart__table__tbody">
                <tr>
                  <td id="totalImport">00</td>
                  <td id="discount">00</td>
                  <td><?php echo IGV; ?>%</td>
                  <td id="totalPay">00</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="cart__client">
            <h2 class="cart__subtitle">Datos del cliente</h2>
            <form action="<?php echo SERVER_URL; ?>/fetch/getDataClientFetch.php" method="POST" class="client__search">
              <select name="tx_search" id="selectSearchFor" class="client__select">
                <option value="RUC">RUC</option>
                <option value="dni" selected>DNI</option>
              </select>
              <input type="text" class="client__inputSearch" name="tx_dni_ruc" id="dni_ruc" placeholder="Escriba el dni" number required>
              <button class="client__btnSearch"><i class="ph ph-magnifying-glass"></i></button>
            </form>
            <form action="" class="form__client" id="form__client">
              <input type="text" name="tx_cliente_id" id="clientId">
              <div class="form__group">
                <label for="clientDNI" class="form__label">DNI</label>
                <input type="text" name="tx_cliente_dni" id="clientDNI" class="form__input" minlength="8" maxlength="8" number>
              </div>
              <div class="form__group">
                <label for="clientRUC" class="form__label">RUC</label>
                <input type="text" name="tx_cliente_RUC" id="clientRUC" class="form__input" minlength="11" maxlength="11" number>
              </div>
              <div class="form__group">
                <label for="clientNames" class="form__label">Nombres</label>
                <input type="text" name="tx_cliente_names" id="clientNames" class="form__input" mayus>
              </div>
              <div class="form__group">
                <label for="clientLastnames" class="form__label">Apellidos</label>
                <input type="text" name="tx_cliente_lastnames" id="clientLastnames" class="form__input" mayus>
              </div>
              <hr>
              <div class="form__group">
                <label for="discount" class="form__label">DESCUENTO S/</label>
                <input type="number" name="tx_cliente_discount" id="discountvalue" class="form__input" placeholder="Descuento a aplicar" decimal>
              </div>
              <button class="form__submit" id="btnApplyDiscount" style="background:var(--c_sky);">Aplicar descuento</button>
              <input type="text" class="form__submit" value="Generar venta">
            </form>
          </div>
        </div>
      </div>
    </div>
    <script src="<?php echo SERVER_URL; ?>/Views/js/main.js"></script>
    <script src="<?php echo SERVER_URL; ?>/Views/js/alerts.js"></script>
    <script src="<?php echo SERVER_URL; ?>/Views/js/cart.js"></script>
    <script src="<?php echo SERVER_URL; ?>/Views/js/<?php echo $vista; ?>.js"></script>
  <?php
    include "./Views/inc/logout.php";
    include "./Views/inc/scriptMenuBar.php";
  } ?>
</body>