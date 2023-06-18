<div class="flexnav">
  <h1 class="titleView">Gestión de categorías de productos</h1>
  <div class="buttons">
    <button class="buttons_btn toggleForm" style="--cl:var(--c_yellow);">Nuevo categoría</button>
    <a href="<?php echo SERVER_URL; ?>/reports/categorias.php" class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</a>
  </div>
</div>
<div class="categorysBox">
  <?php
  require_once "./Controllers/CategoryController.php";
  $IC = new CategoryController();
  $categorys = $IC->getCategoriesController();

  $categorys = json_decode($categorys);
  if (count($categorys) > 0) {
    foreach ($categorys as $key => $category) {
      if (!empty($category->link_image)) $url_img = $category->link_image;
      else if (!empty($category->file_image)) $url_img = $category->file_image;
      else $url_img = "https://img.freepik.com/vector-premium/dispositivos-digitales-realistas-isometria-conjunto-ilustraciones-isometricas_480270-71.jpg";
      echo
      '
      <article class="category" data-key="' . $category->cat_id . '" title="' . $category->description . '">
        <div class="category__imgBox">
          <img src="' . $url_img . '" alt="' . $category->name . '" class="category__img">
        </div>
        <h1 class="category__name">' . $category->name . '</h1>
      </article>
          ';
    }
  } else {
    echo '
      <div class="empty">
        <div class="empty__imgBox"><img src="https://cdn-icons-png.flaticon.com/512/5445/5445197.png" alt="vacio" class="empty__img"></div>
        <p class="empty__message">No hay registros</p>
      </div>
    ';
  }
  ?>
</div>
<div class="modal" id="modalForm">
  <div class="box">
    <form action="<?php echo SERVER_URL; ?>/Request/formCategoryRequest.php" method="POST" enctype="multipart/form-data" class="form form__create formRequest" style="width:100%;">
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
        <legend class="form__legend">Descripción</legend>
        <textarea class="form__textarea" id="descripcion" name="tx_descripcion">
          </textarea>
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
      <input type="submit" value="Agregar" class="form__submit">
    </form>
    <form action="<?php echo SERVER_URL; ?>/Request/deleteCategoryRequest.php" method="POST" class="form formRequest form_delete">
      <input type="hidden" id="categoryIdDel" name="tx_category_idDel">
      <input type="submit" value="Eliminar categoría" class="form__submit" style="background:red;color:#fff; margin-top:10px;">
    </form>
  </div>
</div>