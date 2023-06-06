<div class="flexnav">
  <h1 class="titleView">Gestión de locales</h1>
  <div class="buttons">
    <button class="buttons_btn toggleForm" style="--cl:var(--c_yellow);">Nuevo local</button>
    <a href="<?php echo SERVER_URL; ?>/reports/locales.php" class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</a>
  </div>
</div>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>Nombre</th>
      <th>Dirección</th>
      <th>Tipo</th>
      <th>CanStore</th>
      <th>Acciones</th>
    </thead>
    <tbody class="table__tbody">
      <?php
      require_once "./Controllers/LocalController.php";
      $IL = new LocalController();
      $locations = $IL->getLocalsController();
      $locations = json_decode($locations);
      if (count($locations) > 0) {
        foreach ($locations as $key => $location) {
          $type_local = ($location->type == 1) ? "Tienda" : "Almacén";
          $can_store = $location->canStoreMore ? "Sí" : "No";
          echo
          '
            <tr>
              <td>' . $location->name . '</td>
              <td>' . $location->address . '</td>
              <td>' . $type_local . '</td>
              <td>' . $can_store . '</td>
              <td class="actions">
                <button data-key="' . $location->local_id . '" class="actions__btn btn_edit" style="--cl:var(--c_sky);" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
                <form action="' . SERVER_URL . '/fetch/deleteLocalFetch.php" method="POST" class="formFetch">
                  <input type="hidden" value="' . $location->local_id  . '" name="tx_local_id">
                  <button type="submit" class="actions__btn btn_delete" style="--cl:red;" title="Eliminar"><i class="ph ph-trash"></i></button>
                </form>
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
<div class="modal" id="modalForm">
  <div class="box">
    <form action="<?php echo SERVER_URL; ?>/fetch/formLocalFetch.php" method="POST" class="form form__create formFetch">
      <div class="form__btnclose toggleForm"><i class="ph ph-x"></i></div>
      <h1 class="form__title">Agregar local</h1>
      <input type="hidden" id="localId" name="tx_local_id">
      <fieldset class="form__group">
        <legend class="form__legend">Nombre del local*</legend>
        <input type="text" class="form__input" id="nombre" name="tx_nombre" mayus required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Dirección*</legend>
        <input type="text" class="form__input" id="direccion" name="tx_direccion" mayus required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Tipo de local*</legend>
        <select name="tx_type" class="form__input" required>
          <option selected value="">Seleccione el tipo</option>
          <option value="1">Tienda</option>
          <option value="2">Almacén</option>
        </select>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">CanStore*</legend>
        <select name="tx_canStore" class="form__input" required>
          <option selected value="">Puede almacenar?</option>
          <option value="1">Sí</option>
          <option value="0">No</option>
        </select>
      </fieldset>
      <input type="submit" value="Agregar" class="form__submit">
    </form>
  </div>
</div>