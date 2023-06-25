<div class="flexnav">
  <h1 class="titleView">Gestión de compras</h1>
  <div class="buttons">
    <a href="<?php echo SERVER_URL; ?>/new_purchase" class="buttons_btn" style="--cl:var(--c_yellow);">Nueva compra</a>
    <a href="<?php echo SERVER_URL; ?>/reports/compras.php" class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</a>
  </div>
</div>
<form action="" method="GET" class="filterBox">
  <a href="<?php echo SERVER_URL; ?>/compras" class="filter" id="all">Todos</a>
  <div class="filter">
    <label for="fil_proveedor" class="filter__for">Proveedor: </label>
    <select name="supplier_id" id="fil_proveedor" data-col="supplier_id" class="filter__select">
      <option selected disabled>--</option>
      <?php
      require_once "./Controllers/SupplierController.php";
      $IU = new SupplierController();
      $users = $IU->getSuppliersController();
      $users = json_decode($users);

      foreach ($users as $key => $user) {
        echo '<option value="' . $user->supplier_id . '">' . $user->name . '</option>';
      }
      ?>
    </select>
  </div>
  <div class="filter">
    <label for="fil_usuario" class="filter__for">Usuario: </label>
    <select name="user_id" id="fil_usuario" data-col="user_id" class="filter__select">
      <option selected disabled>--</option>
      <?php
      require_once "./Controllers/UserController.php";
      $IU = new UserController();
      $users = $IU->getUsersController();
      $users = json_decode($users);

      foreach ($users as $key => $user) {
        echo '<option value="' . $user->user_id . '">' . $user->names . ' ' . $user->lastnames . '</option>';
      }
      ?>
    </select>
  </div>
  <div class="filter">
    <label for="fil__ini" class="filter__for">Fecha: </label>
    <input type="date" name="date_start" id="fil__ini" class="filter__date">
    <input type="date" name="date_end" id="fil__end" class="filter__date">
  </div>
  <button type="submit" class="filter filter__btn" title="Filtrar">Filtrar</button>
</form>
<p class="table__numrows">Mostrando
  <strong id="rowsCount">
    <?php
    require_once "./Controllers/PurchaseController.php";

    $IP = new PurchaseController();

    $rows = 50;
    $page = MainModel::getCleanGetValue('page');

    if (!isset($page) || empty($page)) $page = 1;

    $start = is_numeric($page) ? ($page - 1) * $rows : 0;

    $total_purchases = json_decode($IP->getPurchasesController());
    $purchases = json_decode($IP->getPurchasesController($start, $rows));
    $pages = ceil(count($total_purchases) / $rows);

    echo count($purchases);
    ?>
  </strong>de <?php echo count($total_purchases); ?> Compra(s) encontrados
</p>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>Responsable</th>
      <th>Proveedor</th>
      <th>Total</th>
      <th>Fecha</th>
      <th>Acciones</th>
    </thead>
    <tbody class="table__tbody">
      <?php
      if (count($purchases) > 0) {
        foreach ($purchases as $purchase) {
          echo '
          <tr>
            <td>' . $purchase->user . '</td>
            <td>' . $purchase->supplier . '</td>
            <td>S/' . $purchase->total_pay . '</td>
            <td class="nowrap">' . date("d-m-Y", strtotime($purchase->created_at)) . '</td>
            <td>
              <div class="actions">
                <a href="' . SERVER_URL . '/purchase?purchase_id=' . $IP->encryption($purchase->sell_code) . '" class="actions__btn" style="--cl:var(--c_green);"><i class="ph ph-note"></i> Ver detalles</a>
              </div>
            </td>
            </tr>
            ';
        }
      } else {
        echo '
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
      ?>
    </tbody>
  </table>
</div>
<div>
  <ul class="pager">
    <?php
    $params = MainModel::getParamsUrl();
    $params_url = "";
    if (!empty($params)) {
      unset($params['page']);
      foreach ($params as $key => $param) {
        $params_url .= "$key=$params[$key]&";
      }
    };
    $prev = $page > 1 ? "" : "disabled";

    $next = $page < $pages ? "" : "disabled";

    echo '
    <li class="pager__li"><a class="pager__link" href="' . SERVER_URL . '/compras?' . $params_url . 'page=1"><i class="ph ph-caret-double-left"></i></a></li>
    <li class="pager__li"><a class="pager__link ' . $prev . '" href="' . SERVER_URL . '/compras?' . $params_url . 'page=' . $page - 1 . '"><i class="ph ph-caret-left"></i></a></li>
    ';

    $n_page_start = ($page - 2) > 1 ? $page - 2 : 1;
    $n_page_end = ($n_page_start + 4) > $pages ? $pages : $n_page_start + 4;

    for ($i = $n_page_start; $i <= $n_page_end; $i++) {
      $select = $i == $page ? "selected" : "";
      echo '<li class="pager__li"><a class="pager__link ' . $select . '" href="' . SERVER_URL . '/compras?' . $params_url . 'page=' . $i . '">' . $i . '</a></li>';
    }

    echo '
    <li class="pager__li"><a class="pager__link ' . $next . '" href="' . SERVER_URL . '/compras?' . $params_url . 'page=' . $page + 1 . '"><i class="ph ph-caret-right"></i></a></li>
    <li class="pager__li"><a class="pager__link" href="' . SERVER_URL . '/compras?' . $params_url . 'page=' . $pages . '"><i class="ph ph-caret-double-right"></i></a></li>
    ';
    ?>
  </ul>
</div>