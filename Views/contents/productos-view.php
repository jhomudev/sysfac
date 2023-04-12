<div class="flexnav">
  <div class="browser">
    <label for="inputSearch" class="browser__label">Buscar producto</label>
    <input type="search" class="browser__input" id="inputSearch" placeholder="Escribe el nombre del producto">
  </div>
  <div class="buttons">
    <button class="buttons_btn" style="--cl:var(--c_yellow);">Nuevo producto</button>
    <button class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</button>
  </div>
</div>
<div class="filterBox">
  <div class="filter">
    <h2 class="filter__for">Todos</h2>
  </div>
  <div class="filter">
    <label for="fil_almacen" class="filter__for">Almacén: </label>
    <select name="tx_almacen" id="fil_almacen" class="filter__select">
      <option selected disabled>--</option>
      <option value="">A-1</option>
      <option value="">A-2</option>
      <option value="">A-3</option>
    </select>
  </div>
  <div class="filter">
    <label for="fil_categoria" class="filter__for">Categoría: </label>
    <select name="tx_categoria" id="fil_categoria" class="filter__select">
      <option selected disabled>--</option>
      <option value="">Impresión</option>
      <option value="">Periféricos</option>
      <option value="">Sonido</option>
      <option value="">Laptops</option>
      <option value="">Pc</option>
    </select>
  </div>
  <div class="filter">
    <label for="fil_activo" class="filter__for">Activo: </label>
    <select name="tx_categoria" id="fil_activo" class="filter__select">
      <option selected disabled>--</option>
      <option value="1">Sí</option>
      <option value="0">No</option>
    </select>
  </div>
</div>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>Imagen</th>
      <th>Nombre</th>
      <th>Precio venta</th>
      <th>Unidad</th>
      <th>Categoría</th>
      <th>Activo</th>
      <th>Acciones</th>
    </thead>
    <tbody class="table__tbody">
      <tr>
        <td><img src="https://cdn-icons-png.flaticon.com/512/7078/7078310.png" alt="producto_name"></td>
        <td>Impresora EPSON L3250</td>
        <td>S/650</td>
        <td>Unidad</td>
        <td>Impresión</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i></button>
        </td>
      </tr>
      <tr>
        <td><img src="https://cdn-icons-png.flaticon.com/512/7078/7078310.png" alt="producto_name"></td>
        <td>Impresora EPSON L3250</td>
        <td>S/650</td>
        <td>Unidad</td>
        <td>Impresión</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i></button>
        </td>
      </tr>
      <tr>
        <td><img src="https://cdn-icons-png.flaticon.com/512/7078/7078310.png" alt="producto_name"></td>
        <td>Impresora EPSON L3250</td>
        <td>S/650</td>
        <td>Unidad</td>
        <td>Impresión</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i></button>
        </td>
      </tr>
      <tr>
        <td><img src="https://cdn-icons-png.flaticon.com/512/7078/7078310.png" alt="producto_name"></td>
        <td>Impresora EPSON L3250</td>
        <td>S/650</td>
        <td>Unidad</td>
        <td>Impresión</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i></button>
        </td>
      </tr>
      <tr>
        <td><img src="https://cdn-icons-png.flaticon.com/512/7078/7078310.png" alt="producto_name"></td>
        <td>Impresora EPSON L3250</td>
        <td>S/650</td>
        <td>Unidad</td>
        <td>Impresión</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i></button>
        </td>
      </tr>
      <tr>
        <td><img src="https://cdn-icons-png.flaticon.com/512/7078/7078310.png" alt="producto_name"></td>
        <td>Impresora EPSON L3250</td>
        <td>S/650</td>
        <td>Unidad</td>
        <td>Impresión</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i></button>
        </td>
      </tr>
      <tr>
        <td><img src="https://cdn-icons-png.flaticon.com/512/7078/7078310.png" alt="producto_name"></td>
        <td>Impresora EPSON L3250</td>
        <td>S/650</td>
        <td>Unidad</td>
        <td>Impresión</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i></button>
        </td>
      </tr>
      <tr>
        <td><img src="https://cdn-icons-png.flaticon.com/512/7078/7078310.png" alt="producto_name"></td>
        <td>Impresora EPSON L3250</td>
        <td>S/650</td>
        <td>Unidad</td>
        <td>Impresión</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i></button>
        </td>
      </tr>
    </tbody>
  </table>
</div>