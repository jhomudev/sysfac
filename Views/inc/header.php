<header class="header">
  <span class="header__welcome">Hola <b><?php echo $_SESSION['username'] ?></b></span>
  <div class="user">
    <div class="user__icon" id="btnUserbar">
      <i class="ph ph-user-circle"></i>
    </div>
    <div class="user__details">
      <h1 class="user__name"><?php echo $_SESSION['names'] . ' ' . $_SESSION['lastnames'] ?></h1>
      <span class="user__type"><?php echo ($_SESSION['type'] == USER_TYPE->admin) ? "ADMINISTRADOR" : (($_SESSION['type'] == USER_TYPE->superadmin) ? "SUPERADMINISTRADOR" : "VENDEDOR"); ?></span>
    </div>
  </div>
</header>