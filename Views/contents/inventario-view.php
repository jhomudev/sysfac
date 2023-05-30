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
  <div class="browser">
    <label for="inputSearch" class="browser__label">Buscar producto</label>
    <input type="search" class="browser__input" id="inputSearch" placeholder="Escribe el nombre del producto o serie">
  </div>
  <div class="buttons">
    <button class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</button>
  </div>
</div>
<div class="filterBox">
  <div class="filter" id="all">
    <h2 class="filter__for">Todos</h2>
  </div>
  <div class="filter">
    <label class="filter__for">Producto: </label>
    <select name="tx_producto" data-col="product_id" class="filter__select">
      <option selected disabled>--</option>
      <?php
      require_once "./Controllers/ProductController.php";
      $IP = new ProductController();
      $_POST['words'] = "";
      $_POST['column'] = "";
      $_POST['value'] = "";
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
    <select name="tx_state" data-col="state" class="filter__select">
      <option selected disabled>--</option>
      <option value="<?php echo STATE_IN->stock; ?>">En stock</option>
      <option value="<?php echo STATE_IN->damaged; ?>">Dañado</option>
      <option value="<?php echo STATE_IN->sold; ?>">Vendido</option>
    </select>
  </div>
  <div class="filter">
    <label class="filter__for">Local: </label>
    <select name="tx_local" data-col="local_id" class="filter__select">
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
</div>
<p class="table__numrows"><strong id="rowsCount">--</strong> Producto(s) encontrados</p>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>Producto</th>
      <th>Número de serie</th>
      <th>Local</th>
      <th>Estado</th>
      <th>Acciones</th>
    </thead>
    <tbody class="table__tbody">
      <!-- peticion -->
    </tbody>
  </table>
</div>
<div class="modal" id="modalForm">
  <div class="box">
    <form action="<?php echo SERVER_URL; ?>/fetch/formProductFetch.php" method="POST" class="form form__create formFetch">
      <div class="form__btnclose toggleForm"><i class="ph ph-x"></i></div>
      <h1 class="form__title">Agregar producto</h1>
      <div class="form__imgBox">
        <img src="https://cdn-icons-png.flaticon.com/512/7078/7078310.png" class="form__img" alt="producto">
      </div>
      <input type="hidden" id="productIdName" name="tx_product_id">
      <fieldset class="form__group">
        <legend class="form__legend">Nombre*</legend>
        <input type="text" class="form__input" id="nombre" name="tx_nombre">
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Precio de venta*</legend>
        <input type="text" class="form__input" id="precio" name="tx_precio" decimal>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Unidad*</legend>
        <input type="text" class="form__input" id="unidad" name="tx_unidad" mayus>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Mínimo en en inventario*</legend>
        <input type="text" class="form__input" id="unidad" name="tx_minimo" mayus>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Imagen</legend>
        <input type="text" class="form__input" id="linkImage" name="tx_linkImage" placeholder="Link de la imagen">
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Categoría*</legend>
        <select name="tx_category" id="acceso" class="form__input">
          <option selected disabled>Seleccione la categoría</option>
          <?php
          require_once "./Controllers/CategoryController.php";
          $IP = new CategoryController();
          $categories = $IP->getCategoriesController();
          $categories = json_decode($categories);

          foreach ($categories as $key => $category) {
            echo '<option value="' . $category->cat_id . '">' . $category->name . '</option>';
          }
          ?>
        </select>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Activo*</legend>
        <select name="tx_activo" id="activo" class="form__input">
          <option selected disabled>Asigne el estado</option>
          <option value="<?php echo STATE->active ?>">Sí</option>
          <option value="<?php echo STATE->inactive  ?>">No</option>
        </select>
      </fieldset>
      <input type="submit" value="Agregar" class="form__submit">
    </form>
  </div>
</div>