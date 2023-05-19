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
        <!-- <h2 class="cart__title">Carrito de venta</h2> -->
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
            <h2 class="cart__subtitle" style="margin-top:10px;">Datos del cliente</h2>
            <div class="client__search">
              <input type="text" class="client__inputSearch" name="tx_dni_ruc" id="dni_ruc" placeholder="Escriba el DNI" number required>
              <button class="client__btnSearch" id="btnClientSearch"><i class="ph ph-magnifying-glass"></i></button>
            </div>
            <form action="<?php echo SERVER_URL; ?>/fetch/generateSellFetch.php" method="POST" class="form__client formFetch" id="formSell">
              <div class="form__part part_details_client">
                <div class="form__data__client">
                  <input type="hidden" name="tx_client_id" id="clientId">
                  <fieldset class="form__group">
                    <legend class="form__legend">DNI</legend>
                    <input type="text" name="tx_cliente_dni" id="clientDNI" class="form__input" minlength="8" maxlength="8" number>
                  </fieldset>
                  <fieldset class="form__group">
                    <legend class="form__legend">RUC</legend>
                    <input type="text" name="tx_cliente_RUC" id="clientRUC" class="form__input" minlength="11" maxlength="11" number>
                  </fieldset>
                  <fieldset class="form__group">
                    <legend class="form__legend">Nombres</legend>
                    <input type="text" name="tx_cliente_names" id="clientNames" class="form__input" mayus>
                  </fieldset>
                  <fieldset class="form__group">
                    <legend class="form__legend">Apellidos</legend>
                    <input type="text" name="tx_cliente_lastnames" id="clientLastnames" class="form__input" mayus>
                  </fieldset>
                </div>
              </div>
              <div class="form__part part_details_sell">
                <h2 class="cart__subtitle">Detalles de venta</h2>
                <fieldset class="form__group">
                  <legend class="form__legend">Tipo de comprobante</legend>
                  <select name="tx_proof_type" id="typeProof" class="form__input">
                    <option selected disabled>Seleccione el tipo de comprobante</option>
                    <option value="<?php echo TYPE_PROOF->factura; ?>">Factura</option>
                    <option value="<?php echo TYPE_PROOF->boleta; ?>">Boleta de venta</option>
                  </select>
                </fieldset>
                <fieldset class="form__group">
                  <legend class="form__legend">DESCUENTO S/</legend>
                  <input type="number" name="tx_cliente_discount" id="discountvalue" class="form__input" placeholder="Descuento a aplicar" decimal>
                </fieldset>
                <button class="form__submit" id="btnApplyDiscount" style="background:var(--c_sky);">Aplicar descuento</button>
              </div>
              <input type="submit" class="form__submit" value="Generar venta">
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