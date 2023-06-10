<?php

$requestFetch = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (isset($_SESSION['token'])) {
  require_once "../Controllers/PurchaseController.php";
  $IP = new PurchaseController();

  $purchases = json_decode($IP->getPurchasesController());

  $res = "";
  if (count($purchases) > 0) {
    foreach ($purchases as $key => $purchase) {
      $res .= '
      <tr>
        <td>' . $purchase->user . '</td>
        <td>' . $purchase->supplier . '</td>
        <td>S/' . $purchase->total_pay . '</td>
        <td class="nowrap">' . date("d-m-Y", strtotime($purchase->created_at)) . '</td>
        <td class="actions">
        <a href="' . SERVER_URL . '/purchase?purchase_id=' . $IP->encryption($purchase->sell_code) . '" class="actions__btn" style="--cl:var(--c_green);"><i class="ph ph-note"></i> Ver detalles</a>
        </td>
        </tr>
        ';
    }
  } else {
    $res .= '
    <tr>
      <td aria-colspan="7" colspan="7">
        <div class="empty">
          <div class="empty__imgBox"><img src="https://cdn-icons-png.flaticon.com/512/5445/5445197.png" alt="vacio" class="empty__img"></div>
          <p class="empty__message">No hay registros</p>
        </div>
      </td>
    </tr>
    ';
  }

  echo $res;
} else {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}
