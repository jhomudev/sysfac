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
  <form action="" method="POST" class="browser">
    <label for="inputSearch" class="browser__label">Buscar producto</label>
    <input type="search" class="browser__input" name="words_in" id="inputSearch" placeholder="Escribe el nombre del producto o serie" required>
    <button type="submit" class="form__submit" style="width:min-content; padding:7px 10px; font-size:medium; font-weight:bold; display:grid;" title="Buscar"><i class="ph ph-magnifying-glass"></i></button>
  </form>
  <div class="buttons">
    <a href="<?php echo SERVER_URL; ?>/reports/inventario.php" class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</a>
  </div>
</div>
<form action="" method="POST" class="filterBox">
  <div class="filter" id="all">
    <a href="" class="filter__for">Todos</a>
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
        echo '<option value="' . $product->product_id . '">' . $product->name . '</option>';
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
  <button type="submit" class="filter" title="Filtrar" style="border-radius: 3px; padding:7px 10px;">Filtrar</button>
</form>
<p class="table__numrows">
  <strong id="rowsCount">
    <?php
    require_once "./Controllers/ProductController.php";

    $IP = new ProductController();

    $products_all = json_decode($IP->getProductsInventaryController());

    echo count($products_all);
    ?>
  </strong> Producto(s) encontrados
  <?php
  if (isset($_POST['words_in'])) echo ' relacionados a "' . $_POST['words_in'] . '"';
  ?>
</p>
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
      <select name="local" data-col="local" class="form__input" required>
        <option selected disabled>Seleccione el local</option>
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
      <select name="state" data-col="state" class="form__input" required>
        <option selected disabled>Seleccione el estado</option>
        <option value="<?php echo STATE_IN->stock; ?>">En stock</option>
        <option value="<?php echo STATE_IN->damaged; ?>">Dañado</option>
      </select>
    </fieldset>
    <input type="submit" value="Ejecutar" class="form__submit">
  </form>
</div>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th><input type="checkbox" name="checkboxMain" id="checkboxMain"></th>
      <th>Producto</th>
      <th>Número de serie</th>
      <th>Local</th>
      <th>Estado</th>
    </thead>
    <tbody class="table__tbody">
      <?php
      require_once "./Controllers/ProductController.php";

      $IP = new ProductController();

      $products_all = json_decode($IP->getProductsInventaryController());

      if (count($products_all) > 0) {

        foreach ($products_all as $product) {
          $local = isset($product->local_name) ? $product->local_name : "No asignado";
          $ns = $product->serial_number ? $product->serial_number : "N.A.";
          echo '
            <tr>
              <td><input type="checkbox" name="p_checkeds[]" value="' . $product->product_unit_id . '"></td>
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