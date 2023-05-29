<?php

require_once "./Controllers/PurchaseController.php";
$IP = new PurchaseController();

$url_actual = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
// Descomponemos la URL
$componentes_url = parse_url($url_actual);
// Obtenemos los valores de los parámetros
parse_str($componentes_url['query'], $parametros);

$purchase_id_enc = $parametros['purchase_id'];
$purchase_id = $IP->decryption($purchase_id_enc);
$_GET['purchase_id'] = $purchase_id;

$purchase = json_decode($IP->getDataPurchaseController());
if ($purchase == []) {
  echo '<h1 class="purchase__title"> Compra ' . $purchase_id_enc . ' no existe</h1>';
  exit();
}
$data = $purchase->data;
$ops = $purchase->operations;
?>

<div class="block__head">
  <h1 class="purchase__title">Detalles de compra/abastecimiento</h1>
  <nav class="nav__views">
    <ul class="nav__views__ul">
      <li class="nav__views__li">
        <a href="<?php echo SERVER_URL; ?>/dashboard" class="nav__views__link">Home</a>
      </li>
      <li>
        <a href="<?php echo SERVER_URL; ?>/compras" class="nav__views__link">Compras</a>
      </li>
      <li>
        <a href="" class="nav__views__link">Detalles de compra</a>
      </li>
    </ul>
  </nav>
</div>
<div class="purchase__data">
  <fieldset class="form__group">
    <legend class="form__label">Responsable</legend>
    <p class="form__input"><?php echo $data->user; ?></p>
  </fieldset>
  <fieldset class="form__group">
    <legend class="form__label">Proveedor</legend>
    <p class="form__input"><?php echo $data->supplier; ?></p>
  </fieldset>
  <fieldset class="form__group">
    <legend class="form__label">Total pagado</legend>
    <p class="form__input">S/<?php echo $data->total; ?></p>
  </fieldset>
  <fieldset class="form__group">
    <legend class="form__label">Fecha</legend>
    <p class="form__input"><?php echo date("d-m-Y", strtotime($data->created_at)); ?></p>
  </fieldset>
  <fieldset class="form__group">
    <legend class="form__label">Información adicional</legend>
    <p class="form__input"><?php echo $data->additional_info ? $data->additional_info : "No hay detalles adicionales"; ?></p>
  </fieldset>
</div>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>Producto</th>
      <th>Numero de serie</th>
      <th>Costo</th>
      <th>Cantidad</th>
      <th>Total</th>
    </thead>
    <tbody class="table__tbody">
      <?php
      foreach ($ops as $op) {
        $ns = $op->serial_number ? $op->serial_number : "N.A.";
        echo '
          <tr>
            <td>' . $op->product . '</td>
            <td>' . $ns . '</td>
            <td>S/' . $op->price . '</td>
            <td>' . $op->quantity . '</td>
            <td>S/' . $op->import . '</td>
          </tr>
        ';
      }
      ?>
    </tbody>
  </table>
</div>