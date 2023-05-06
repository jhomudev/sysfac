<div class="flexnav">
  <div class="browser">
    <label for="inputSearch" class="browser__label">Buscar proveedor</label>
    <input type="search" class="browser__input" id="inputSearch" placeholder="Escribe el nombre del proveedor">
  </div>
  <div class="buttons">
    <button class="buttons_btn toggleForm" style="--cl:var(--c_yellow);">Nuevo proveedor</button>
    <button class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</button>
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
      <tr>
        <td>12345678909</td>
        <td>System S.A.C.</td>
        <td>Jr. Lima S/N</td>
        <td>966340233</td>
        <td>12-04-2019</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>12345678909</td>
        <td>System S.A.C.</td>
        <td>Jr. Lima S/N</td>
        <td>966340233</td>
        <td>12-04-2019</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>12345678909</td>
        <td>System S.A.C.</td>
        <td>Jr. Lima S/N</td>
        <td>966340233</td>
        <td>12-04-2019</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>12345678909</td>
        <td>System S.A.C.</td>
        <td>Jr. Lima S/N</td>
        <td>966340233</td>
        <td>12-04-2019</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>12345678909</td>
        <td>System S.A.C.</td>
        <td>Jr. Lima S/N</td>
        <td>966340233</td>
        <td>12-04-2019</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>12345678909</td>
        <td>System S.A.C.</td>
        <td>Jr. Lima S/N</td>
        <td>966340233</td>
        <td>12-04-2019</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>12345678909</td>
        <td>System S.A.C.</td>
        <td>Jr. Lima S/N</td>
        <td>966340233</td>
        <td>12-04-2019</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>12345678909</td>
        <td>System S.A.C.</td>
        <td>Jr. Lima S/N</td>
        <td>966340233</td>
        <td>12-04-2019</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<div class="modalForm">
  <div class="box">
    <form action="POST" class="form formFetch">
      <button class="form__btnclose toggleForm"><i class="ph ph-x"></i></button>
      <h1 class="form__title">Agregar proveedor</h1>
      <fieldset class="form__group">
        <legend class="form__legend">RUC</legend>
        <input type="text" class="form__input" id="ruc" name="tx_ruc" maxlength="11" minlength="11" number>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Nombre del proveedor</legend>
        <input type="text" class="form__input" id="nombre" name="tx_nombre">
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Dirección</legend>
        <input type="text" class="form__input" id="direccion" name="tx_direccion">
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Teléfono</legend>
        <input type="text" class="form__input" id="telefono" name="tx_telefono" number>
      </fieldset>
      <input type="submit" value="Agregar" class="form__submit">
    </form>
  </div>
</div>