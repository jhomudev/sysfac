<?php

$requestFetch = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (isset($_SESSION['token'])) {
  require_once "../Controllers/SellController.php";
  $IS = new SellController();

  $sells = json_decode($IS->getSellsController());

  $res = "";
  if (count($sells) > 0) {
    foreach ($sells as $key => $sell) {
      $res .= '
      <tr>
        <td>' . $sell->user . '</td>
        <td>' . $sell->client . '</td>
        <td>S/' . $sell->total_import . '</td>
        <td>S/' . $sell->discount . '</td>
        <td>S/' . $sell->total_pay . '</td>
        <td>' . $sell->created_at . '</td>
        <td class="actions">
          <a href="' . SERVER_URL . '/proof?proof_code=' . $IS->encryption($sell->proof_code) . '" target="_blank" class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-newspaper-clipping"></i></a>
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
