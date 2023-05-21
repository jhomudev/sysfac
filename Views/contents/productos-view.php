<div class="flexnav">
  <div class="browser">
    <label for="inputSearch" class="browser__label">Buscar producto</label>
    <input type="search" class="browser__input" id="inputSearch" placeholder="Escribe el nombre del producto">
  </div>
  <div class="buttons">
    <button class="buttons_btn toggleForm" style="--cl:var(--c_yellow);">Nuevo producto</button>
    <button class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</button>
  </div>
</div>
<div class="filterBox">
  <div class="filter" id="all">
    <h2 class="filter__for">Todos</h2>
  </div>
  <div class="filter">
    <label for="fil_categoria" class="filter__for">Categoría: </label>
    <select name="tx_categoria" data-col="category_id" class="filter__select">
      <option selected disabled>--</option>
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
  </div>
  <div class="filter">
    <label for="fil_activo" class="filter__for">Activo: </label>
    <select name="tx_categoria" data-col="is_active" class="filter__select">
      <option selected disabled>--</option>
      <option value="<?php echo STATE->active; ?>">Sí</option>
      <option value="<?php echo STATE->inactive; ?>">No</option>
    </select>
  </div>
  <a href="<?php echo SERVER_URL; ?>/inventario" class="filter__products_all_btn">Productos en inventario</a>
</div>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>Imagen</th>
      <th>Nombre</th>
      <th>Precio venta</th>
      <th>Unidad</th>
      <th>Venta por</th>
      <th>Categoría</th>
      <th>Activo</th>
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
      <input type="hidden" id="productId" name="tx_product_id">
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
        <input type="number" class="form__input" id="minimo" name="tx_minimo" number>
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
        <legend class="form__legend">Venta por: *</legend>
        <select name="tx_sale_for" id="sale_for" class="form__input">
          <option selected disabled>Asigne un valor</option>
          <option value="<?php echo ADD_FOR->quantity ?>">Cantidad</option>
          <option value="<?php echo ADD_FOR->serial_number  ?>">Unidad/Número de serie</option>
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