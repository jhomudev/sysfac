<div class="flexnav">
  <div class="browser">
    <label for="inputSearch" class="browser__label">Buscar cliente</label>
    <input type="search" class="browser__input" name="tx_words" id="inputSearch" placeholder="Escribe el nombre del cliente">
  </div>
  <div class="buttons">
    <a href="<?php echo SERVER_URL; ?>/reports/clientes.php" class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</a>
  </div>
</div>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>DNI</th>
      <th>RUC</th>
      <th>Nombres y Apellidos</th>
      <th>Teléfono</th>
      <th>Correo</th>
      <th>Dirección</th>
      <th>Acciones</th>
    </thead>
    <tbody class="table__tbody">
      <!-- Peticion  -->
    </tbody>
  </table>
</div>
<div class="modal" id="modalForm">
  <div class="box">
    <form action="<?php echo SERVER_URL; ?>/Request/formClientRequest.php" method="POST" class="form form__edit formRequest">
      <div class="form__btnclose toggleForm"><i class="ph ph-x"></i></div>
      <h1 class="form__title">Datos del cliente</h1>
      <input type="hidden" class="form__input" id="userId" name="tx_client_id">
      <fieldset class="form__group">
        <legend class="form__legend">DNI</legend>
        <input type="text" class="form__input" id="dni" name="tx_client_dni" maxlength="8" minlength="8" number>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">RUC</legend>
        <input type="text" class="form__input" id="RUC" name="tx_client_RUC" maxlength="11" minlength="11" number>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Nombres</legend>
        <input type="text" class="form__input" id="nombres" name="tx_client_names" mayus required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Apellidos</legend>
        <input type="text" class="form__input" id="apellidos" name="tx_client_lastnames" mayus required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Teléfono</legend>
        <input type="text" class="form__input" id="phone" name="tx_client_phone" maxlength="9" minlength="9" number>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Dirección</legend>
        <input type="text" class="form__input" id="address" name="tx_client_address" mayus>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Correo</legend>
        <input type="email" class="form__input" id="correo" name="tx_client_email">
      </fieldset>
      <input type="submit" value="Actualizar" class="form__submit">
    </form>
  </div>
</div>