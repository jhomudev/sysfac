<header class="header">
  <span class="header__welcome">Hola <b><?php echo $_SESSION['username'] ?></b></span>
  <div class="header__options">
    <div class="notifications header__option">
      <div class="header__icon" id="btnNotibar">
        <i class="ph ph-bell-simple"></i>
      </div>
      <div class="notifications__details">
        <h1 class="notifications__details__title">Notificaciones</h1>
        <div class="notifications__box">
          <a href="" class="notification">
            <h2 class="notification__title">Titulo de notificación</h2>
            <p class="notification__p">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Iste aperiam dolorem culpa. Natus amet accusantium aliquam corporis consequatur. Dolore aperiam consequuntur veniam. Magnam architecto corrupti optio numquam, temporibus adipisci eum?</p>
          </a>
          <a href="" class="notification">
            <h2 class="notification__title">Titulo de notificación</h2>
            <p class="notification__p">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Iste aperiam dolorem culpa. Natus amet accusantium aliquam corporis consequatur. Dolore aperiam consequuntur veniam. Magnam architecto corrupti optio numquam, temporibus adipisci eum?</p>
          </a>
          <a href="" class="notification">
            <h2 class="notification__title">Titulo de notificación</h2>
            <p class="notification__p">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Iste aperiam dolorem culpa. Natus amet accusantium aliquam corporis consequatur. Dolore aperiam consequuntur veniam. Magnam architecto corrupti optio numquam, temporibus adipisci eum?</p>
          </a>
          <a href="" class="notification">
            <h2 class="notification__title">Titulo de notificación</h2>
            <p class="notification__p">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Iste aperiam dolorem culpa. Natus amet accusantium aliquam corporis consequatur. Dolore aperiam consequuntur veniam. Magnam architecto corrupti optio numquam, temporibus adipisci eum?</p>
          </a>
          <a href="" class="notification">
            <h2 class="notification__title">Titulo de notificación</h2>
            <p class="notification__p">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Iste aperiam dolorem culpa. Natus amet accusantium aliquam corporis consequatur. Dolore aperiam consequuntur veniam. Magnam architecto corrupti optio numquam, temporibus adipisci eum?</p>
          </a>
          <a href="" class="notification">
            <h2 class="notification__title">Titulo de notificación</h2>
            <p class="notification__p">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Iste aperiam dolorem culpa. Natus amet accusantium aliquam corporis consequatur. Dolore aperiam consequuntur veniam. Magnam architecto corrupti optio numquam, temporibus adipisci eum?</p>
          </a>
        </div>
      </div>
    </div>
    <div class="cart_icon toggleShowCart header__icon"><span class="cart_icon_count"></span><i class="ph ph-shopping-cart"></i></div>
    <div class="user header__option">
      <div class="user__icon header__icon" id="btnUserbar">
        <i class="ph ph-user-circle"></i>
      </div>
      <div class="user__details">
        <h1 class="user__name"><?php echo $_SESSION['names'] . ' ' . $_SESSION['lastnames'] ?></h1>
        <span class="user__type"><?php echo ($_SESSION['type'] == USER_TYPE->admin) ? "ADMINISTRADOR" : (($_SESSION['type'] == USER_TYPE->superadmin) ? "SUPERADMINISTRADOR" : "VENDEDOR"); ?></span>
      </div>
    </div>
  </div>
</header>