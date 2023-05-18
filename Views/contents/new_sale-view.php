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
</div>
<div class="productsBox">
  <!-- peticion -->
</div>