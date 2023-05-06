<div class="flexnav">
  <h1 class="titleView">Gestión de categorías de productos</h1>
  <!-- <div class="browser">
    <label for="inputSearch" class="browser__label">Buscar categoría</label>
    <input type="search" class="browser__input" id="inputSearch" placeholder="Escribe el nombre del categoría">
  </div> -->
  <div class="buttons">
    <button class="buttons_btn toggleForm" style="--cl:var(--c_yellow);">Nuevo categoría</button>
    <button class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</button>
  </div>
</div>
<div class="categorysBox">
  <?php
  require_once "./Controllers/CategoryController.php";
  $IP = new CategoryController();
  $categorys = $IP->getCategoriesController();
  $categorys = json_decode($categorys);
  if (count($categorys) > 0) {
    foreach ($categorys as $key => $category) {
      echo
      '
      <article class="category" data-key="' . $category->cat_id . '" title="' . $category->description . '">
        <div class="category__imgBox">
          <img src="' . $category->link_image . '" alt="' . $category->name . '" class="category__img">
        </div>
        <h1 class="category__name">' . $category->name . '</h1>
      </article>
          ';
    }
  } else {
    echo '
        <tr>
          <td aria-colspan="7" colspan="7">
            <div class="empty">
              <div class="empty__imgBox"><img src="https://cdn-icons-png.flaticon.com/512/5445/5445197.png" alt="vacio" class="empty__img"></div>
            </div>
            <p class="empty__message">No hay registros</p>
          </td>
        </tr>
        ';
  }
  ?>


</div>
<div class="modal" id="modalForm">
  <div class="box">
    <form action="<?php echo SERVER_URL; ?>/fetch/formCategoryFetch.php" method="POST" class="form form__create formFetch" style="width:100%;">
      <div class="form__btnclose toggleForm"><i class="ph ph-x"></i></div>
      <h1 class="form__title">Agregar categoría</h1>
      <div class="form__imgBox">
        <img src="https://cdn-icons-png.flaticon.com/512/1524/1524855.png" class="form__img" alt="producto">
      </div>
      <input type="hidden" id="categoryId" name="tx_category_id">
      <fieldset class="form__group">
        <legend class="form__legend">Nombre de la categoría *</legend>
        <input type="text" class="form__input" id="nombreCategoria" name="tx_nombre" mayus>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Imagen</legend>
        <input type="text" class="form__input" id="linkImage" name="tx_linkImage" placeholder="Link de la imagen">
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Descripción</legend>
        <textarea class="form__textarea" id="descripcion" name="tx_descripcion">
        </textarea>
      </fieldset>
      <input type="submit" value="Agregar" class="form__submit">
    </form>
    <form action="<?php echo SERVER_URL; ?>/fetch/deleteCategoryFetch.php" method="POST" class="form formFetch">
      <input type="hidden" id="categoryIdDel" name="tx_category_idDel">
      <input type="submit" value="Eliminar" class="form__submit" style="background:red;color:#fff">
    </form>
  </div>
</div>