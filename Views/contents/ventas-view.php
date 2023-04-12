<div class="flexnav">
  <div class="browser">
    <label for="inputSearch" class="browser__label">Buscar venta</label>
    <input type="search" class="browser__input" id="inputSearch" placeholder="Escribe término a buscar">
  </div>
  <div class="buttons">
    <button class="buttons_btn" style="--cl:var(--c_yellow);">Nueva venta</button>
    <button class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</button>
  </div>
</div>
<div class="filterBox">
  <div class="filter">
    <h2 class="filter__for">Todos</h2>
  </div>
  <div class="filter">
    <label for="fil_almacen" class="filter__for">Usuario: </label>
    <select name="tx_almacen" id="fil_almacen" class="filter__select">
      <option selected disabled>--</option>
      <option value="">Tulio Ormeño</option>
      <option value="">Isaias Morlaes</option>
      <option value="">Lucía Navarro</option>
    </select>
  </div>
  <div class="filter">
    <label for="fil__ini" class="filter__for">Fecha: </label>
    <input type="date" name="tx_start" id="fil__ini" class="filter__date">
    <input type="date" name="tx_end" id="fil__end" class="filter__date">
  </div>
</div>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>Usuario</th>
      <th>Cliente</th>
      <th>Descuento</th>
      <th>Importe total</th>
      <th>Fecha</th>
      <th>Acciones</th>
    </thead>
    <tbody class="table__tbody">
      <tr>
        <td>Tulio Ormeño</td>
        <td>Julian Casabalncas</td>
        <td>S/25</td>
        <td>S/250</td>
        <td>13-02-2021</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i> Ver detalles</button>
        </td>
      </tr>
      <tr>
        <td>Tulio Ormeño</td>
        <td>Julian Casabalncas</td>
        <td>S/25</td>
        <td>S/250</td>
        <td>13-02-2021</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i> Ver detalles</button>
        </td>
      </tr>
      <tr>
        <td>Tulio Ormeño</td>
        <td>Julian Casabalncas</td>
        <td>S/25</td>
        <td>S/250</td>
        <td>13-02-2021</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i> Ver detalles</button>
        </td>
      </tr>
      <tr>
        <td>Tulio Ormeño</td>
        <td>Julian Casabalncas</td>
        <td>S/25</td>
        <td>S/250</td>
        <td>13-02-2021</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i> Ver detalles</button>
        </td>
      </tr>
      <tr>
        <td>Tulio Ormeño</td>
        <td>Julian Casabalncas</td>
        <td>S/25</td>
        <td>S/250</td>
        <td>13-02-2021</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i> Ver detalles</button>
        </td>
      </tr>
      <tr>
        <td>Tulio Ormeño</td>
        <td>Julian Casabalncas</td>
        <td>S/25</td>
        <td>S/250</td>
        <td>13-02-2021</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i> Ver detalles</button>
        </td>
      </tr>
      <tr>
        <td>Tulio Ormeño</td>
        <td>Julian Casabalncas</td>
        <td>S/25</td>
        <td>S/250</td>
        <td>13-02-2021</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i> Ver detalles</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>