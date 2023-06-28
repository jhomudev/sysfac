<?php
$request = false;
require_once "./Controllers/ViewController.php";
$IV = new ViewController();
$vista = $IV->getViewController();

if ($vista == "proof") {
  include_once "./Views/contents/$vista-view.php";
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <link rel="shortcut icon" href="<?php echo SERVER_URL ?>/Views/assets/iconLogo.png" type="image/x-icon">
  <?php
  if ($vista === "login") echo '<link rel="stylesheet" href="' . SERVER_URL . '/Views/css/login.css">';
  else if ($vista !== "login" || $vista !== "404") echo '<link rel="stylesheet" href="' . SERVER_URL . '/Views/css/panel.css">';

  if ($vista == "dashboard") echo '<link rel="stylesheet" href="' . SERVER_URL . '/Views/css/dashboard.css">';
  ?>
  <?php  ?>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <title><?php echo COMPANY ?></title>
</head>

<body>
  <?php
  if ($vista == "login" || $vista == "404") {
    include_once "./Views/contents/$vista-view.php";
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
        <?php
        include_once "./views/inc/header.php";

        // Condicionales para las vistas dependiendo del tipo de usuario
        $access_views_vendedor = ["ventas", "new_sale", "clientes", "dashboard"];

        $style = "";/* para el height del main view , para vista dont_access */

        if ($_SESSION['type'] == USER_TYPE->vendedor) {
          if (in_array($vista, $access_views_vendedor)) $view_show = "./Views/contents/" . $vista . "-view.php";
          else {
            $view_show = "./Views/inc/dont_access.php";
            $style = "style='height:100%;'";
          }
        } else  $view_show = "./Views/contents/" . $vista . "-view.php";
        ?>
        <main class="view" <?php echo $style; ?>>
          <?php include_once $view_show; ?>
        </main>
      </div>
      <div class="cart__modal">
        <div class="cart" id="cart">
          <div class="cart__tableBox">
            <table class="cart__table">
              <thead class="cart__table__thead">
                <th>Producto</th>
                <th>N.S.</th>
                <th>P.U.</th>
                <th>Cantidad</th>
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
            <form action="<?php echo SERVER_URL; ?>/Request/generateSellRequest.php" method="POST" class="form__client formRequest" id="formSell">
              <div class="form__part part_details_client">
                <div class="form__data__client">
                  <input type="hidden" name="tx_person_id" id="personId">
                  <fieldset class="form__group">
                    <legend class="form__legend">Nombre</legend>
                    <input type="text" name="tx_person_name" id="personName" class="form__input" disabled>
                  </fieldset>
                  <fieldset class="form__group">
                    <legend class="form__legend">Estado</legend>
                    <input type="text" name="tx_person_state" id="personState" class="form__input" disabled>
                  </fieldset>
                  <fieldset class="form__group">
                    <legend class="form__legend">Nombres</legend>
                    <input type="text" name="tx_person_names" id="personNames" class="form__input" disabled>
                  </fieldset>
                  <fieldset class="form__group">
                    <legend class="form__legend">Apellidos</legend>
                    <input type="text" name="tx_person_lastnames" id="personLastnames" class="form__input" disabled>
                  </fieldset>
                </div>
              </div>
              <div class="form__part part_details_sell">
                <h2 class="cart__subtitle">Detalles de venta</h2>
                <fieldset class="form__group">
                  <legend class="form__legend">Tipo de comprobante</legend>
                  <select name="tx_proof_type" id="typeProof" class="form__input">
                    <option value="" selected disabled>Seleccione el tipo de comprobante</option>
                    <option value="<?php echo TYPE_PROOF->factura; ?>">Factura</option>
                    <option value="<?php echo TYPE_PROOF->boleta; ?>">Boleta de venta</option>
                  </select>
                </fieldset>
                <fieldset class="form__group">
                  <legend class="form__legend">DESCUENTO</legend>
                  <select name="tx_discount_type" id="typeDiscount" class="form__input">
                    <option selected disabled>Tipo de descuento</option>
                    <option value="<?php echo DISCOUNT->percentage; ?>">Porcentual</option>
                    <option value="<?php echo DISCOUNT->absolute; ?>">Valor absoluto</option>
                  </select>
                  <br>
                  <br>
                  <input type="number" name="tx_cliente_discount" id="discountValue" class="form__input" placeholder="Descuento a aplicar" decimal>
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
    <!-- Script charjs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <?php
    $no_script_views = ['compras', "ventas"];
    if (!in_array($vista, $no_script_views)) echo '<script src="' . SERVER_URL . '/Views/js/' . $vista . '.js"></script>';

    include "./Views/inc/logout.php";
    include "./Views/inc/scriptMenuBar.php";
  } ?>
</body>