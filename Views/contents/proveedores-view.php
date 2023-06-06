<div class="flexnav">
  <h1 class="titleView">Gestión de proveedores</h1>
  <div class="buttons">
    <button class="buttons_btn toggleForm" style="--cl:var(--c_yellow);">Nuevo proveedor</button>
    <a href="<?php echo SERVER_URL; ?>/reports/proveedores.php" class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</a>
  </div>
</div>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>RUC</th>
      <th>Nombre</th>
      <th>Dirección</th>
      <th>Teléfono</th>
      <th>Fecha de adición</th>
      <th>Acciones</th>
    </thead>
    <tbody class="table__tbody">
      <?php
      require_once "./Controllers/SupplierController.php";
      $IS = new SupplierController();
      $suppliers = $IS->getSuppliersController();
      $suppliers = json_decode($suppliers);
      if (count($suppliers) > 0) {
        foreach ($suppliers as $key => $supplier) {
          echo
          '
          <tr>
            <td>' . $supplier->RUC  . '</td>
            <td>' . $supplier->name  . '</td>
            <td>' . $supplier->address . '</td>
            <td>' . $supplier->phone . '</td>
            <td>' . date("d-m-Y", strtotime($supplier->created_at)) . '</td>
            <td class="actions">
              <button data-key="' . $supplier->supplier_id . '" class="actions__btn btn_edit toggleForm" style="--cl:var(--c_sky);" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
              <form action="' . SERVER_URL . '/fetch/deleteSupplierFetch.php" method="POST" class="formFetch">
                <input type="hidden" value="' . $supplier->supplier_id  . '" name="tx_supplier_id">
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
    <form method="POST" action="<?php echo SERVER_URL; ?>/fetch/formSupplierfetch.php" class="form formCreate formFetch">
      <div class="form__btnclose toggleForm"><i class="ph ph-x"></i></div>
      <h1 class="form__title">Agregar proveedor</h1>
      <input type="hidden" id="supplierIdRUC" name="tx_supplier_id">
      <fieldset class="form__group">
        <legend class="form__legend">RUC</legend>
        <input type="text" class="form__input" id="ruc" name="tx_ruc" maxlength="11" minlength="11" number>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Nombre del proveedor</legend>
        <input type="text" class="form__input" id="nombre" name="tx_nombre" mayus>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Dirección</legend>
        <input type="text" class="form__input" id="direccion" name="tx_direccion" mayus>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Teléfono</legend>
        <input type="text" class="form__input" id="telefono" name="tx_telefono" maxlength="9" minlength="9" number>
      </fieldset>
      <input type="submit" value="Agregar" class="form__submit">
    </form>
  </div>
</div>