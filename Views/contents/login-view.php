<div class="container_all">
  <h1 class="section__welcome">Bienvenido a su sistema</h1>
  <div class="logo">
    <img class="logo__img" src="http://localhost/sysfac/Views/assets/logo.png" alt="">
  </div>
  <form action="" method="POST" class="form">
    <h2 class="form__title">Ingresa a tu cuenta</h2>
    <div class="form__group">
      <i class="ph ph-user"></i><input class="form__input" type="text" name="tx_username" value="<?php echo isset($_POST['tx_username']) ? $_POST['tx_username'] : ''; ?>" placeholder="Nombre de usuario" required />
    </div>
    <div class="form__group">
      <i class="ph ph-lock-key"></i><input class="form__input" type="password" name="tx_password" value="<?php echo isset($_POST['tx_password']) ? $_POST['tx_password'] : ''; ?>" placeholder="Contraseña" required />
    </div>
    <input class="form__submit" type="submit" value="Ingresar" />
  </form>
</div>
<?php

require_once "./Controllers/LoginController.php";

$login = new LoginController();

if (isset($_POST['tx_username']) && isset($_POST['tx_password'])) echo $login->LoginController();
else echo $login->forceLogin();

?>