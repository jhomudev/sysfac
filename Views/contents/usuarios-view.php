<div class="flexnav">
  <h1 class="titleView">Gestión de usuarios</h1>
  <!-- <div class="browser">
    <label for="inputSearch" class="browser__label">Buscar usuario</label>
    <input type="search" class="browser__input" id="inputSearch" placeholder="Escribe el nombre del usuario">
  </div> -->
  <div class="buttons">
    <button class="buttons_btn toggleForm" style="--cl:var(--c_yellow);">Nuevo usuario</button>
    <button class="buttons_btn" style="--cl:var(--c_orange);">Generar reporte</button>
  </div>
</div>
<div class="tableBox">
  <table class="table">
    <thead class="table__thead">
      <th>DNI</th>
      <th>Nombres y Apellidos</th>
      <th>Correo</th>
      <th>Acceso</th>
      <th>Activo</th>
      <th>Acciones</th>
    </thead>
    <tbody class="table__tbody">
      <?php
      require_once "./Controllers/UserController.php";
      $IU = new UserController();
      $users = $IU->getUsersController();
      $users = json_decode($users);
      if (count($users) > 0) {
        foreach ($users as $key => $user) {
          $type = ($user->type == USER_TYPE->superadmin) ? "SUPERADMIN" : (($user->type  == USER_TYPE->admin) ? "ADMINISTRADOR" : "VENDEDOR");
          $is_active = ($user->is_active) ? "SI" : "NO";
          echo
          '
          <tr>
            <td>' . $user->dni  . '</td>
            <td>' . $user->names  . ' ' . $user->lastnames  . '</td>
            <td>' . $user->email . '</td>
            <td>' . $type . '</td>
            <td>' . $is_active . '</td>
            <td class="actions">
              <button data-key="' . $user->user_id . '" class="actions__btn btn_edit btnToggleForm" style="--cl:var(--c_sky);" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
              <form action="' . SERVER_URL . '/fetch/deleteUserFetch.php" method="POST" class="formFetch">
                <input type="hidden" value="' . $user->user_id  . '" name="tx_user_id">
                <button type="submit" class="actions__btn btn_delete" style="--cl:red;" title="Eliminar"><i class="ph ph-trash"></i></button>
              </form>
              <button data-key="' . $user->user_id  . '" class="actions__btn toggleDetails" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i></button>
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
            </div>
            <p class="empty__message">No hay registros</p>
          </td>
        </tr>
        ';
      }
      ?>
      <!-- <tr>
        <td>71749122</td>
        <td>Jhonan caleb muñoz carrillo</td>
        <td>99999999</td>
        <td>jhonna@gmail.com</td>
        <td>Administrador</td>
        <td>Sí</td>
        <td class="actions">
          <button class="actions__btn" style="--cl:red;" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <button class="actions__btn" style="--cl:var(--c_sky);" title="Eliminar"><i class="ph ph-trash"></i></button>
          <button class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i></button>
        </td>
      </tr> -->
    </tbody>
  </table>
</div>
<div class="modal" id="modalForm">
  <div class="box">
    <form action="<?php echo SERVER_URL; ?>/fetch/formUserFetch.php" method="POST" class="form form__create formFetch">
      <div class="form__btnclose toggleForm"><i class="ph ph-x"></i></div>
      <h1 class="form__title">Agregar usuario</h1>
      <fieldset class="form__group">
        <legend class="form__legend">DNI</legend>
        <input type="hidden" class="form__input" id="userId" name="tx_user_id">
        <input type="text" class="form__input" id="dni" name="tx_dni" maxlength="8" minlength="8" number required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Nombres</legend>
        <input type="text" class="form__input" id="nombres" name="tx_nombres" mayus required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Apellidos</legend>
        <input type="text" class="form__input" id="apellidos" name="tx_apellidos" mayus required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Nombre de usuario</legend>
        <input type="text" class="form__input" id="username" name="tx_username" required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Contraseña</legend>
        <input type="text" class="form__input" id="password" name="tx_password" required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Correo</legend>
        <input type="email" class="form__input" id="correo" name="tx_correo" required>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Acceso</legend>
        <select name="tx_acceso" id="acceso" class="form__input" required>
          <option value="" selected>Asigne el acceso</option>
          <option value="1">Superadministrador</option>
          <option value="2">Administrador</option>
          <option value="3">Vendedor</option>
        </select>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__legend">Activo</legend>
        <select name="tx_activo" id="activo" class="form__input" required>
          <option value="" selected>Asigne el estado</option>
          <option value="1">Sí</option>
          <option value="0">No</option>
        </select>
      </fieldset>
      <input type="submit" value="Agregar" class="form__submit">
    </form>
  </div>
</div>
<div class="modal" id="modalDetails">
  <div class="details">
    <div class="details__btnclose toggleDetails"><i class="ph ph-x"></i></div>
    <h1 class="details__title">Detalles de usuario</h1>
    <table class="details__table">
      <tbody class="details__table__tbody">
        <tr>
          <th>DNI</th>
          <td id="userDNI"></td>
        </tr>
        <tr>
          <th>Nombres</th>
          <td id="userNames"></td>
        </tr>
        <tr>
          <th>Apellidos</th>
          <td id="userLastnames"></td>
        </tr>
        <tr>
          <th>Nombre de usuario</th>
          <td id="userUsername"></td>
        </tr>
        <tr>
          <th>Contraseña</th>
          <td id="userPassword"></td>
        </tr>
        <tr>
          <th>Correo</th>
          <td id="userEmail"></td>
        </tr>
        <tr>
          <th>Acceso</th>
          <td id="userIsAdmin"></td>
        </tr>
        <tr>
          <th>Activo</th>
          <td id="userIsActive"></td>
        </tr>
        <tr>
          <th>Fecha de creación</th>
          <td id="userDate"></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>