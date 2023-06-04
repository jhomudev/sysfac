<div class="flexnav">
  <h1 class="titleView">Gestión de compras</h1>
  <div class="buttons">
    <a href="<?php echo SERVER_URL; ?>/new_purchase" class="buttons_btn" style="--cl:var(--c_yellow);">Nueva compra</a>
    <button class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</button>
  </div>
</div>
<div class="filterBox">
  <div class="filter">
    <h2 class="filter__for" id="all">Todos</h2>
  </div>
  <div class="filter">
    <label for="fil_proveedor" class="filter__for">Proveedor: </label>
    <select name="tx_proveedor" id="fil_proveedor" data-col="supplier_id" class="filter__select">
      <option selected disabled>--</option>
      <?php
      require_once "./Controllers/SupplierController.php";
      $IU = new SupplierController();
      $users = $IU->getSuppliersController();
      $users = json_decode($users);

      foreach ($users as $key => $user) {
        echo '<option value="' . $user->supplier_id . '">' . $user->name . '</option>';
      }
      ?>
    </select>
  </div>
  <div class="filter">
    <label for="fil_usuario" class="filter__for">Usuario: </label>
    <select name="tx_usuario" id="fil_usuario" data-col="user_id" class="filter__select">
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
      <th>Responsable</th>
      <th>Proveedor</th>
      <th>Total</th>
      <th>Fecha</th>
      <th>Acciones</th>
    </thead>
    <tbody class="table__tbody">
      <!-- peticion -->
    </tbody>
  </table>
</div>