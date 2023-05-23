<div class="flexnav">
  <div class="browser">
    <label for="inputSearch" class="browser__label">Buscar venta</label>
    <input type="search" class="browser__input" id="inputSearch" placeholder="Por cliente, comprobante">
  </div>
  <div class="buttons">
    <a href="<?php echo SERVER_URL; ?>/new_sale" class="buttons_btn" style="--cl:var(--c_yellow);">Nueva venta</a>
    <button class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</button>
  </div>
</div>
<div class="filterBox">
  <div class="filter">
    <h2 class="filter__for" id="all">Todos</h2>
  </div>
  <div class="filter">
    <label for="fil_user" class="filter__for">Usuario: </label>
    <select name="tx_user" id="fil_user" data-col="user_id" class="filter__select">
      <option selected disabled>--</option>
      <?php
      require_once "./Controllers/UserController.php";
      $IU = new UserController();
      $users = $IU->getUsersController();
      $users = json_decode($users);

      foreach ($users as $key => $user) {
        echo '<option value="' . $user->user_id . '">' . $user->names . ' ' . $user->lastnames . '</option>';
      }
      ?>
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
      <th>Importe total</th>
      <th>Descuento</th>
      <th>Total pagado</th>
      <th>Fecha</th>
      <th>Detalles</th>
    </thead>
    <tbody class="table__tbody">
      <!-- peticion -->
    </tbody>
  </table>
</div>