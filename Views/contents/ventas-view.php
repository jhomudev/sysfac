<div class="flexnav">
  <form action="" method="GET" class="browser">
    <label for="inputSearch" class="browser__label">Buscar venta</label>
    <input type="search" class="browser__input" name="words" id="inputSearch" placeholder="Por cliente, comprobante">
    <button type="submit" class="form__submit" style="width:min-content; padding:7px 10px; font-size:medium; font-weight:bold; display:grid;" title="Buscar"><i class="ph ph-magnifying-glass"></i></button>
  </form>
  <div class="buttons">
    <a href="<?php echo SERVER_URL; ?>/new_sale" class="buttons_btn" style="--cl:var(--c_yellow);">Nueva venta</a>
    <a href="<?php echo SERVER_URL; ?>/reports/ventas.php" class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</a>
  </div>
</div>
<form action="" method="GET" class="filterBox">
  <div class="filter">
    <a href="<?php echo SERVER_URL; ?>/ventas" class="filter__for" id="all">Todos</a>
  </div>
  <div class="filter">
    <label for="fil_user" class="filter__for">Usuario: </label>
    <select name="user_id" id="fil_user" data-col="user_id" class="filter__select">
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
    require_once "./Controllers/SellController.php";

    $IS = new SellController();

    $rows = 50;
    $page = MainModel::getCleanGetValue('page');

    if (!isset($page) || empty($page)) $page = 1;

    $start = is_numeric($page) ? ($page - 1) * $rows : 0;

    $total_sells = json_decode($IS->getSellsController());
    $sells = json_decode($IS->getSellsController($start, $rows));
    $pages = ceil(count($total_sells) / $rows);

    echo count($sells);
    ?>
  </strong>de <?php echo count($total_sells); ?> Venta(s) encontrados
  <?php
  if (!empty(MainModel::getCleanGetValue('words'))) echo ' relacionados a "' . MainModel::getCleanGetValue('words') . '"';
  ?>
</p>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>Usuario</th>
      <th>Cliente</th>
      <th>Importe total</th>
      <th>Descuento</th>
      <th>Total pagado</th>
      <th>Fecha</th>
      <th>Detalles</th>
    </thead>
    <tbody class="table__tbody">
      <?php
      if (count($sells) > 0) {
        foreach ($sells as $sell) {
          echo '
          <tr>
            <td>' . $sell->user . '</td>
            <td>' . $sell->client . '</td>
            <td>S/' . $sell->total_import . '</td>
            <td>S/' . $sell->discount . '</td>
            <td>S/' . $sell->total_pay . '</td>
            <td class="nowrap">' . date('d-m-Y', strtotime($sell->created_at)) . '</td>
            <td>
              <div class="actions">
                <a href="' . SERVER_URL . '/proof?proof_code=' . $IS->encryption($sell->proof_code) . '" target="_blank" class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-newspaper-clipping"></i></a>
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
    <li class="pager__li"><a class="pager__link" href="' . SERVER_URL . '/ventas?' . $params_url . 'page=1"><i class="ph ph-caret-double-left"></i></a></li>
    <li class="pager__li"><a class="pager__link ' . $prev . '" href="' . SERVER_URL . '/ventas?' . $params_url . 'page=' . $page - 1 . '"><i class="ph ph-caret-left"></i></a></li>
    ';

    $n_page_start = ($page - 2) > 1 ? $page - 2 : 1;
    $n_page_end = ($n_page_start + 4) > $pages ? $pages : $n_page_start + 4;

    for ($i = $n_page_start; $i <= $n_page_end; $i++) {
      $select = $i == $page ? "selected" : "";
      echo '<li class="pager__li"><a class="pager__link ' . $select . '" href="' . SERVER_URL . '/ventas?' . $params_url . 'page=' . $i . '">' . $i . '</a></li>';
    }

    echo '
    <li class="pager__li"><a class="pager__link ' . $next . '" href="' . SERVER_URL . '/ventas?' . $params_url . 'page=' . $page + 1 . '"><i class="ph ph-caret-right"></i></a></li>
    <li class="pager__li"><a class="pager__link" href="' . SERVER_URL . '/ventas?' . $params_url . 'page=' . $pages . '"><i class="ph ph-caret-double-right"></i></a></li>
    ';
    ?>
  </ul>
</div>