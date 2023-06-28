<div class="block__head">
  <h1 class="purchase__title">Productos en inventario</h1>
  <nav class="nav__views">
    <ul class="nav__views__ul">
      <li class="nav__views__li">
        <a href="<?php echo SERVER_URL; ?>/dashboard" class="nav__views__link">Home</a>
      </li>
      <li class="nav__views__li">
        <a href="<?php echo SERVER_URL; ?>/productos" class="nav__views__link">Productos</a>
      </li>
      <li class="nav__views__li">
        <a href="" class="nav__views__link">Inventario</a>
      </li>
    </ul>
  </nav>
</div>
<div class="flexnav">
  <form action="" method="GET" class="browser">
    <label for="inputSearch" class="browser__label">Buscar producto</label>
    <input type="search" class="browser__input" name="words_in" id="inputSearch" placeholder="Escribe el nombre del producto o serie" required>
    <button type="submit" class="form__submit" style="width:min-content; padding:7px 10px; font-size:medium; font-weight:bold; display:grid;" title="Buscar"><i class="ph ph-magnifying-glass"></i></button>
  </form>
  <div class="buttons">
    <a href="<?php echo SERVER_URL; ?>/reports/inventario.php" class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</a>
  </div>
</div>
<form action="" method="GET" class="filterBox">
  <div class="filter" id="all">
    <a href="<?php echo SERVER_URL; ?>/inventario" class="filter__for">Todos</a>
  </div>
  <div class="filter">
    <label class="filter__for">Producto: </label>
    <select name="product_id" data-col="product_id" class="filter__select" required>
      <option selected disabled>--</option>
      <?php
      require_once "./Controllers/ProductController.php";
      $IP = new ProductController();

      $products = $IP->getProductsController();
      $products = json_decode($products);


      foreach ($products as $key => $product) {
        $product_name = $product->name;
        $limited_text = substr($product->name, 0, 30);
        if (strlen($product_name) > 20) {
          $limited_text .= "...";
        }
        $product_name = $limited_text;
        echo '<option value="' . $product->product_id . '">' . $product_name . '</option>';
      }
      ?>
    </select>
  </div>
  <div class="filter">
    <label class="filter__for">Estado: </label>
    <select name="state" data-col="state" class="filter__select" required>
      <option selected disabled>--</option>
      <option value="<?php echo STATE_IN->stock; ?>">En stock</option>
      <option value="<?php echo STATE_IN->damaged; ?>">Dañado</option>
      <option value="<?php echo STATE_IN->sold; ?>">Vendido</option>
    </select>
  </div>
  <div class="filter">
    <label class="filter__for">Local: </label>
    <select name="local_id" data-col="local_id" class="filter__select" required>
      <option selected disabled>--</option>
      <option value="unassigned">Sin asignar</option>
      <?php
      require_once "./Controllers/LocalController.php";
      $IL = new LocalController();
      $locations = $IL->getLocalsController();
      $locations = json_decode($locations);

      foreach ($locations as $key => $local) {
        echo '<option value="' . $local->local_id . '">' . $local->name . '</option>';
      }
      ?>
    </select>
  </div>
  <button type="submit" class="filter filter__btn" title="Filtrar">Filtrar</button>
</form>
<div class="actions__prods">
  <form action="<?php echo SERVER_URL; ?>/Request/formProductInvRequest.php" method="POST" class="form__prod box__entries formRequest">
    <fieldset class="form__group">
      <legend class="form__label">Acción</legend>
      <select name="action" id="action" class="form__input" required>
        <option value="" selected disabled>Seleccione una acción</option>
        <option value="assign_local">Asignar local a producto(s)</option>
        <option value="change_state">Cambiar estado de producto(s)</option>
      </select>
    </fieldset>
    <fieldset class="form__group hidden" id="localBox">
      <legend class="form__label">Local</legend>
      <select name="local" data-col="local" class="form__input">
        <option value="" selected disabled>Seleccione el local</option>
        <?php
        require_once "./Controllers/LocalController.php";
        $IL = new LocalController();
        $locations = $IL->getLocalsController();
        $locations = json_decode($locations);

        foreach ($locations as $local) {
          if ($local->canStoreMore) echo '<option value="' . $local->local_id . '">' . $local->name . '</option>';
        }
        ?>
      </select>
    </fieldset>
    <fieldset class="form__group hidden" id="stateBox">
      <legend class="form__label">Estado</legend>
      <select name="state" data-col="state" class="form__input">
        <option value="" selected disabled>Seleccione el estado</option>
        <option value="<?php echo STATE_IN->stock; ?>">En stock</option>
        <option value="<?php echo STATE_IN->damaged; ?>">Dañado</option>
      </select>
    </fieldset>
    <input type="submit" value="Ejecutar" class="form__submit">
  </form>
</div>
<p class="table__numrows">
  <strong id="rowsCount">
    <?php
    require_once "./Controllers/ProductController.php";

    $IP = new ProductController();
    $rows = 50;
    $page = MainModel::getCleanGetValue('page');

    if (!isset($page) || empty($page)) $page = 1;

    $start = is_numeric($page) ? ($page - 1) * $rows : 0;

    $total_products_all = json_decode($IP->getProductsInventaryController());
    $products_all = json_decode($IP->getProductsInventaryController($start, $rows));
    $pages = ceil(count($total_products_all) / $rows);

    echo count($products_all);
    ?>
  </strong>de <?php echo count($total_products_all); ?> Unidad(es) encontradas
  <?php
  if (!empty(MainModel::getCleanGetValue('words_in'))) echo ' relacionados a "' . MainModel::getCleanGetValue('words_in') . '"';
  ?>
</p>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th><input type="checkbox" name="checkboxMain" id="checkboxMain" class="checkbox__inv"></th>
      <th>Producto</th>
      <th>Número de serie</th>
      <th>Local</th>
      <th>Estado</th>
    </thead>
    <tbody class="table__tbody">
      <?php
      if (count($products_all) > 0) {
        foreach ($products_all as $product) {
          $local = isset($product->local_name) ? $product->local_name : "No asignado";
          $ns = $product->serial_number ? $product->serial_number : "N.A.";
          echo '
            <tr>
              <td><label for="cb_' . $product->product_unit_id . '" class="label__checkbox"><input type="checkbox" id="cb_' . $product->product_unit_id . '" class="checkbox__inv" name="p_checkeds[]" value="' . $product->product_unit_id . '"></label></td>
              <td>' . $product->product_name . '</td>
              <td>' . $ns . '</td>
              <td>' . $local . '</td>
              <td>' . $product->state . '</td>
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
    <li class="pager__li"><a class="pager__link" href="' . SERVER_URL . '/inventario?' . $params_url . 'page=1"><i class="ph ph-caret-double-left"></i></a></li>
    <li class="pager__li"><a class="pager__link ' . $prev . '" href="' . SERVER_URL . '/inventario?' . $params_url . 'page=' . $page - 1 . '"><i class="ph ph-caret-left"></i></a></li>
    ';

    $n_page_start = ($page - 2) > 1 ? $page - 2 : 1;
    $n_page_end = ($n_page_start + 4) > $pages ? $pages : $n_page_start + 4;

    for ($i = $n_page_start; $i <= $n_page_end; $i++) {
      $select = $i == $page ? "selected" : "";
      echo '<li class="pager__li"><a class="pager__link ' . $select . '" href="' . SERVER_URL . '/inventario?' . $params_url . 'page=' . $i . '">' . $i . '</a></li>';
    }

    echo '
    <li class="pager__li"><a class="pager__link ' . $next . '" href="' . SERVER_URL . '/inventario?' . $params_url . 'page=' . $page + 1 . '"><i class="ph ph-caret-right"></i></a></li>
    <li class="pager__li"><a class="pager__link" href="' . SERVER_URL . '/inventario?' . $params_url . 'page=' . $pages . '"><i class="ph ph-caret-double-right"></i></a></li>
    ';
    ?>
  </ul>
</div>