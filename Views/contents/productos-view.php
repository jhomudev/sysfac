<div class="flexnav">
  <form action="" method="POST" class="browser">
    <label for="inputSearch" class="browser__label">Buscar producto</label>
    <input type="search" class="browser__input" name="words" id="inputSearch" placeholder="Escribe el nombre del producto" required>
    <button type="submit" class="form__submit" style="width:min-content; padding:7px 10px; font-size:medium; font-weight:bold; display:grid;" title="Buscar"><i class="ph ph-magnifying-glass"></i></button>
  </form>
  <div class="buttons">
    <button class="buttons_btn toggleForm" style="--cl:var(--c_yellow);">Nuevo producto</button>
    <a href="<?php echo SERVER_URL; ?>/reports/productos.php" class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</a>
  </div>
</div>
<form action="" method="POST" class="filterBox">
  <div class="filter" id="all">
    <a href="" class="filter__for">Todos</a>
  </div>
  <div class="filter">
    <label for="fil_categoria" class="filter__for">Categoría: </label>
    <select name="category_id" data-col="category_id" class="filter__select" required>
      <option value="" selected disabled>--</option>
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
    <select name="is_active" data-col="is_active" class="filter__select">
      <option value="" selected disabled>--</option>
      <option value="<?php echo STATE->active; ?>">Sí</option>
      <option value="<?php echo STATE->inactive; ?>">No</option>
    </select>
  </div>
  <button type="submit" class="filter" title="Filtrar" style="border-radius: 3px; padding:7px 10px;">Filtrar</button>
  <a href="<?php echo SERVER_URL; ?>/inventario" class="filter__products_btn">Productos en inventario</a>
</form>
<p class="table__numrows">
  <strong id="rowsCount">
    <?php
    require_once "./Controllers/ProductController.php";

    $IP = new ProductController();
    $products = json_decode($IP->getProductsController());
    // $products = $IP->getProductsController();

    echo count($products);
    ?>
  </strong> Producto(s) encontrados
  <?php
  if (isset($_POST['words_in'])) echo ' relacionados a "' . $_POST['words_in'] . '"';
  ?>
</p>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>Imagen</th>
      <th>Nombre</th>
      <th>Precio venta</th>
      <th>Unidad</th>
      <th>Venta por</th>
      <th>Categoría</th>
      <th>Stock mínimo</th>
      <th>Stock actual</th>
      <th>Activo</th>
      <th>Acciones</th>
    </thead>
    <tbody class="table__tbody">
      <?php
      if (count($products) > 0) {
        foreach ($products as $product) {
          $img = "https://cdn-icons-png.flaticon.com/512/5445/5445197.png";
          $sale_for = $product->sale_for == 1 ? "CANTIDAD" : "UNIDAD/N.S.";
          $is_active = $product->is_active ? "SI" : "NO";
          $color = $product->stock <= $product->inventary_min ? "#F0D0D6" : "";
          if ($product->link_image) $img = $product->link_image;
          if ($product->file_image) $img = $product->file_image;
          echo '
          <tr style="background:' . $color . ';">
            <td><img src="' . $img . '" loading="lazy" class="product__img__table"></td>
            <td>' . $product->name . '</td>
            <td>S/ ' . $product->price_sale . '</td>
            <td>' . $product->unit . '</td>
            <td>' . $sale_for . '</td>
            <td>' . $product->category . '</td>
            <td>' . $product->inventary_min . '</td>
            <td>' . $product->stock . '</td>
            <td>' . $is_active . '</td>
            <td>
              <div class="actions">
                <button data-key="' . $product->product_id . '" class="actions__btn btn__edit" style="--cl:var(--c_sky);" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
                <form action="' . SERVER_URL . '/Request/deleteProductRequest.php" method="POST" class="formRequest formDelete">
                  <input type="hidden" value="' . $product->product_id . '" name="tx_product_id">
                  <button class="actions__btn btn_delete" style="--cl:red;" title="Eliminar"><i class="ph ph-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          ';
        }
      } else {
        echo '
        <tr>
          <td aria-colspan="10" colspan="10">
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
<div class="modal" id="modalForm">
  <div class="box">
    <form action="<?php echo SERVER_URL; ?>/Request/formProductRequest.php" method="POST" class="form form__create formRequest">
      <div class="form__btnclose toggleForm"><i class="ph ph-x"></i></div>
      <h1 class="form__title">Agregar producto</h1>
      <div class="form__imgBox">
        <img src="https://cdn-icons-png.flaticon.com/512/7078/7078310.png" class="form__img" alt="producto">
      </div>
      <input type="hidden" id="productIdName" name="tx_product_id">
      <fieldset class="form__group">
        <legend class="form__legend">Nombre*</legend>
        <input type="text" class="form__input" id="nombre" name="tx_nombre" mayus required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Precio de venta*</legend>
        <input type="text" class="form__input" id="precio" name="tx_precio" decimal required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Unidad*</legend>
        <input type="text" class="form__input" id="unidad" name="tx_unidad" mayus required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Mínimo en inventario*</legend>
        <input type="number" class="form__input" id="minimo" name="tx_minimo" number required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Imagen</legend>
        <div class="linkImage__box">
          <input type="search" class="form__input" id="linkImage" name="tx_linkImage" placeholder="Pegue el link de una imagen">
          <button class="btn__linkImage">Subir</button>
        </div>
        <hr>
        <div class="file__img__box">
          <label for="file_cat" class="file__img__label">Subir imagen</label>
          <input type="file" name="file_cat" id="file_cat" accept=".png,.jpg,.jpeg,.webp">
        </div>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Categoría*</legend>
        <select name="tx_category" id="acceso" class="form__input" required>
          <option value="" selected disabled>Seleccione la categoría</option>
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
        <select name="tx_sale_for" id="sale_for" class="form__input" required>
          <option value="" selected disabled>Asigne un valor</option>
          <option value="<?php echo ADD_FOR->quantity ?>">Cantidad</option>
          <option value="<?php echo ADD_FOR->serial_number  ?>">Unidad/Número de serie</option>
        </select>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Activo*</legend>
        <select name="tx_activo" id="activo" class="form__input" required>
          <option value="" selected disabled>Asigne el estado</option>
          <option value="<?php echo STATE->active ?>">Sí</option>
          <option value="<?php echo STATE->inactive  ?>">No</option>
        </select>
      </fieldset>
      <input type="submit" value="Agregar" class="form__submit">
    </form>
  </div>
</div>