<div class="menuBar">
  <div class="flex">
    <div class="logo">
      <!-- background image del logo -->
    </div>
  </div>
  <nav class="nav">
    <ul>
      <li>
        <a href="<?php echo SERVER_URL; ?>/dashboard" data-view="dashboard" class="nav__link nav_item_link"><i class="ph ph-squares-four"></i><span>Dashboard</span></a>
      </li>
      <?php
      if ($_SESSION['type'] !== USER_TYPE->vendedor) {
      ?>
        <li>
          <a href="<?php echo SERVER_URL; ?>/usuarios" data-view="usuarios" class="nav__link nav_item_link"><i class="ph ph-user-gear"></i><span>Usuarios</span></a>
        </li>
      <?php
      }
      ?>
      <li>
        <a href="<?php echo SERVER_URL; ?>/clientes" data-view="clientes" class="nav__link nav_item_link"><i class="ph ph-users"></i><span>Clientes</span></a>
      </li>
      <?php
      if ($_SESSION['type'] !== USER_TYPE->vendedor) {
      ?>
        <li>
          <a href="<?php echo SERVER_URL; ?>/productos" data-view="productos" class="nav__link nav_item_link"><i class="ph ph-dropbox-logo"></i><span>Productos</span></a>
        </li>
        <li>
          <a href="<?php echo SERVER_URL; ?>/categorias" data-view="categorias" class="nav__link nav_item_link"><i class="ph ph-list-dashes"></i><span>Categorías</span></a>
        </li>
      <?php
      }
      ?>
      <li>
        <a href="<?php echo SERVER_URL; ?>/ventas" data-view="ventas" class="nav__link nav_item_link"><i class="ph ph-ticket"></i><span>Ventas</span></a>
      </li>
      <?php
      if ($_SESSION['type'] !== USER_TYPE->vendedor) {
      ?>
        <li>
          <a href="<?php echo SERVER_URL; ?>/compras" data-view="compras" class="nav__link nav_item_link"><i class="ph ph-shopping-cart"></i><span>Compras</span></a>
        </li>
        <li>
          <a href="<?php echo SERVER_URL; ?>/proveedores" data-view="proveedores" class="nav__link nav_item_link"><i class="ph ph-user-list"></i><span>Proveedores</span></a>
        </li>
        <li>
          <a href="<?php echo SERVER_URL; ?>/locales" data-view="locales" class="nav__link nav_item_link"><i class="ph ph-warehouse"></i><span>Locales</span></a>
        </li>
      <?php
      }
      ?>
    </ul>
    <div class="logout">
      <a data-content="Cerrar sesión" class="nav_item_link btnLogout"><i class="ph ph-sign-out"></i><span>Cerrar sesión</span></a>
    </div>
  </nav>
</div>
<button class="btnToggleBar btnToggleBar_out"><i class="ph ph-list"></i></button>
<div class="menuBar__responsive" id="menuBarResponsive">
  <div class="flex">
    <button class="btnToggleBar"><i class="ph ph-list"></i></button>
    <div class="logo">
      <!-- background image del logo -->
    </div>
  </div>
  <div class="user">
    <p class="user__name">Hola <span><?php echo $_SESSION['username'] ?></span></p>
    <span class="user__type"><?php echo ($_SESSION['type'] == USER_TYPE->admin) ? "ADMINISTRADOR" : (($_SESSION['type'] == USER_TYPE->superadmin) ? "SUPERADMINISTRADOR" : "VENDEDOR"); ?></span>
  </div>
  <nav class="nav">
    <ul>
      <li>
        <div class="cart_icon toggleShowNotibar nav_item_link"><i class="ph ph-bell-simple"><span class="noti_icon_count countBox"></span></i><span>Notificaciones</span></div>
      </li>
      <li>
        <div class="cart_icon toggleShowCart nav_item_link"><i class="ph ph-shopping-cart"><span class="cart_icon_count countBox"></span></i><span>Carrito</span></div>
      </li>
      <div class="logout" style="margin:0; padding:0;"></div>
      <li>
        <a href="<?php echo SERVER_URL; ?>/dashboard" data-view="dashboard" class="res__nav__link nav_item_link"><i class="ph ph-squares-four"></i><span>Dashboard</span></a>
      </li>
      <?php
      if ($_SESSION['type'] !== USER_TYPE->vendedor) {
      ?>
        <li>
          <a href="<?php echo SERVER_URL; ?>/usuarios" data-view="usuarios" class="res__nav__link nav_item_link"><i class="ph ph-user-gear"></i><span>Usuarios</span></a>
        </li>
      <?php
      }
      ?>
      <li>
        <a href="<?php echo SERVER_URL; ?>/clientes" data-view="clientes" class="res__nav__link nav_item_link"><i class="ph ph-users"></i><span>Clientes</span></a>
      </li>
      <?php
      if ($_SESSION['type'] !== USER_TYPE->vendedor) {
      ?>
        <li>
          <a href="<?php echo SERVER_URL; ?>/productos" data-view="productos" class="res__nav__link nav_item_link"><i class="ph ph-dropbox-logo"></i><span>Productos</span></a>
        </li>
        <li>
          <a href="<?php echo SERVER_URL; ?>/categorias" data-view="categorias" class="res__nav__link nav_item_link"><i class="ph ph-list-dashes"></i><span>Categorías</span></a>
        </li>
      <?php
      }
      ?>
      <li>
        <a href="<?php echo SERVER_URL; ?>/ventas" data-view="ventas" class="res__nav__link nav_item_link"><i class="ph ph-ticket"></i><span>Ventas</span></a>
      </li>
      <?php
      if ($_SESSION['type'] !== USER_TYPE->vendedor) {
      ?>
        <li>
          <a href="<?php echo SERVER_URL; ?>/compras" data-view="compras" class="res__nav__link nav_item_link"><i class="ph ph-shopping-cart"></i><span>Compras</span></a>
        </li>
        <li>
          <a href="<?php echo SERVER_URL; ?>/proveedores" data-view="proveedores" class="res__nav__link nav_item_link"><i class="ph ph-user-list"></i><span>Proveedores</span></a>
        </li>
        <li>
          <a href="<?php echo SERVER_URL; ?>/locales" data-view="locales" class="res__nav__link nav_item_link"><i class="ph ph-warehouse"></i><span>Locales</span></a>
        </li>
      <?php
      }
      ?>
    </ul>
    <div class="logout">
      <a class="nav_item_link btnLogout"><i class="ph ph-sign-out"></i><span>Cerrar sesión</span></a>
    </div>
  </nav>
</div>