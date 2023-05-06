<div class="flexnav">
  <div class="browser">
    <label for="inputSearch" class="browser__label">Buscar almacén</label>
    <input type="search" class="browser__input" id="inputSearch" placeholder="Escribe el nombre del almacén">
  </div>
  <div class="buttons">
    <button class="buttons_btn toggleForm" style="--cl:var(--c_yellow);">Nuevo almacén</button>
    <button class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</button>
  </div>
</div>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>Nombre</th>
      <th>Dirección</th>
      <th>CanStore</th>
      <th>Acciones</th>
    </thead>
    <tbody class="table__tbody">
      <tr>
        <td>Almacen N1</td>
        <td>Jr. Lima S/N</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>Almacen N1</td>
        <td>Jr. Lima S/N</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>Almacen N1</td>
        <td>Jr. Lima S/N</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>Almacen N1</td>
        <td>Jr. Lima S/N</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>Almacen N1</td>
        <td>Jr. Lima S/N</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>Almacen N1</td>
        <td>Jr. Lima S/N</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
        </td>
      </tr>
      <tr>
        <td>Almacen N1</td>
        <td>Jr. Lima S/N</td>
        <td>Sí</td>
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
      <h1 class="form__title">Agregar almacén</h1>
      <fieldset class="form__group">
        <legend class="form__legend">Nombre del almacén</legend>
        <input type="text" class="form__input" id="nombre" name="tx_nombre">
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Dirección</legend>
        <input type="text" class="form__input" id="direccion" name="tx_direccion">
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">CanStore</legend>
        <select name="tx_canStore" id="canStore" class="form__input">
          <option selected disabled>Puede almacenar</option>
          <option value="1">Sí</option>
          <option value="0">No</option>
        </select>
      </fieldset>
      <input type="submit" value="Agregar" class="form__submit">
    </form>
  </div>
</div>