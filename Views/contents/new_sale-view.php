<div class="block__head">
  <h1 class="purchase__title">Nueva venta</h1>
  <nav class="nav__views">
    <ul class="nav__views__ul">
      <li class="nav__views__li">
        <a href="<?php echo SERVER_URL; ?>/dashboard" class="nav__views__link">Home</a>
      </li>
      <li class="nav__views__li">
        <a href="<?php echo SERVER_URL; ?>/ventas" class="nav__views__link">Ventas</a>
      </li>
      <li class="nav__views__li">
        <a href="" class="nav__views__link">Nueva venta</a>
      </li>
    </ul>
  </nav>
</div>
<div class="flexnav">
  <div class="browser">
    <label for="inputSearch" class="browser__label">Buscar producto</label>
    <input type="search" class="browser__input" id="inputSearch" placeholder="Escribe el nombre del producto">
  </div>
  <div class="buttons">
    <button class="buttons_btn toggleShowCart" style="--cl:var(--c_yellow);">Ver carrito</button>
  </div>
</div>
<div class="filterBox">
  <div class="filter" id="all">
    <h2 class="filter__for">Todos</h2>
  </div>
  <div class="filter">
    <label for="fil_categoria" class="filter__for">Categoría: </label>
    <select name="category_id" data-col="category_id" class="filter__select">
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
</div>
<div class="productsBox">
  <!-- peticion -->
</div>
<div class="modal" id="modalForm">
  <div class="box">
    <form action="<?php echo SERVER_URL; ?>/Request/cartRequest.php" method="POST" class="form product__form">
      <div class="form__btnclose closeForm"><i class="ph ph-x"></i></div>
      <h1 class="form__title">Agregar a carrito</h1>
      <input type="hidden" class="form__input" id="productIdName" name="tx_product_id">
      <fieldset class="form__group">
        <legend class="form__legend">Producto</legend>
        <input type="text" class="form__input" id="productName" disabled>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Precio S/</legend>
        <input type="number" class="form__input" id="productPrice" number disabled>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Venta por:</legend>
        <input type="text" name="tx_add_for" id="addFor" class="form__input" disabled>
      </fieldset>
      <fieldset class="form__group quantityBox hidden">
        <legend class="form__legend">Cantidad</legend>
        <input type="number" class="form__input" id="quantity" value="1" name="tx_quantity" number>
      </fieldset>
      <fieldset class="form__group nsBox hidden">
        <legend class="form__legend">Número(s) de serie</legend>
        <input type="text" class="form__input" id="ns" name="tx_ns" placeholder="Ejm: NjkJ787J88, T87Y76j88877">
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Detalles</legend>
        <textarea class="form__input" name="tx_details" id="details" rows="6" mayus></textarea>
      </fieldset>
      <input type="hidden" name="action" value="Agregar">
      <input type="submit" value="Agregar" class="form__submit">
    </form>
  </div>
</div>