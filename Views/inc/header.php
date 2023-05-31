<header class="header">
  <span class="header__welcome">Hola <b><?php echo $_SESSION['username'] ?></b></span>
  <div class="header__options">
    <div class="notifications header__option">
      <div class="header__icon toggleShowNotibar" id="btnNotibar">
        <span class="noti_icon_count countBox"></span>
        <i class="ph ph-bell-simple"></i>
      </div>
      <div class="notifications__details">
        <h1 class="notifications__details__title">Notificaciones</h1>
        <div class="notifications__box">
          <?php
          // Notificación de carrito de compra
          if (isset($_SESSION['cart']) && count($_SESSION['cart']['items']) > 0) {
            echo '
                <a href="' . SERVER_URL . '/new_sale" class="notification">
                  <h2 class="notification__title">Venta inconclusa</h2>
                  <p class="notification__p">Hay productos en el carrito de venta, realize la venta o limpie el carrito.</p>
                </a>
              ';
          }

          // Notificación de carrito de compra
          if (isset($_SESSION['cart_purchase']) && count($_SESSION['cart_purchase']['items']) > 0) {
            echo '
                <a href="' . SERVER_URL . '/new_purchase" class="notification">
                  <h2 class="notification__title">Compra inconclusa</h2>
                  <p class="notification__p">Tiene una compra inconclusa, hay productos en la lista de compra.</p>
                </a>
              ';
          }

          // Notificaiones de stock minimo
          $products = MainModel::executeQuerySimple("SELECT pa.product_id, p.name, p.inventary_min FROM products_all pa INNER JOIN products p ON p.product_id=pa.product_id GROUP BY pa.product_id");
          $products = json_decode(json_encode($products->fetchAll()));

          foreach ($products as $product) {
            $stock = MainModel::executeQuerySimple("SELECT COUNT(pa.product_id) FROM products_all pa INNER JOIN products p ON p.product_id=pa.product_id WHERE pa.product_id=$product->product_id AND pa.state=" . STATE_IN->stock)->fetchColumn();
            if ($stock <= $product->inventary_min) {
              echo '
                <a href="' . SERVER_URL . '/productos" class="notification">
                  <h2 class="notification__title">Stock por agotarse</h2>
                  <p class="notification__p">El producto <strong>' . $product->name . '</strong> está en su mínimo de inventario. Realize la compra del producto o cambie de estado a inactivo.</p>
                </a>
              ';
            }
          }
          ?>
        </div>
      </div>
    </div>
    <div class="cart_icon toggleShowCart header__icon"><span class="cart_icon_count countBox"></span><i class="ph ph-shopping-cart"></i></div>
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
<div class="notifications__details__responsive">
  <div class="notifications__details__responsive__close"><i class="ph ph-x"></i></div>
  <h1 class="notifications__details__title">Notificaciones</h1>
  <div class="notifications__box">
    <?php
    // Notificación de carrito de compra
    if (isset($_SESSION['cart']) && count($_SESSION['cart']['items']) > 0) {
      echo '
          <a href="' . SERVER_URL . '/new_sale" class="notification">
            <h2 class="notification__title">Venta inconclusa</h2>
            <p class="notification__p">Hay productos en el carrito de venta, realize la venta o limpie el carrito.</p>
          </a>
        ';
    }

    // Notificación de carrito de compra
    if (isset($_SESSION['cart_purchase']) && count($_SESSION['cart_purchase']['items']) > 0) {
      echo '
          <a href="' . SERVER_URL . '/new_purchase" class="notification">
            <h2 class="notification__title">Compra inconclusa</h2>
            <p class="notification__p">Tiene una compra inconclusa, hay productos en la lista de compra.</p>
          </a>
        ';
    }

    // Notificaiones de stock minimo
    $products = MainModel::executeQuerySimple("SELECT pa.product_id, p.name, p.inventary_min FROM products_all pa INNER JOIN products p ON p.product_id=pa.product_id GROUP BY pa.product_id");
    $products = json_decode(json_encode($products->fetchAll()));

    foreach ($products as $product) {
      $stock = MainModel::executeQuerySimple("SELECT COUNT(pa.product_id) FROM products_all pa INNER JOIN products p ON p.product_id=pa.product_id WHERE pa.product_id=$product->product_id AND pa.state=" . STATE_IN->stock)->fetchColumn();
      if ($stock <= $product->inventary_min) {
        echo '
          <a href="' . SERVER_URL . '/productos" class="notification">
            <h2 class="notification__title">Stock por agotarse</h2>
            <p class="notification__p">El producto <strong>' . $product->name . '</strong> está en su mínimo de inventario. Realize la compra del producto o cambie de estado a inactivo.</p>
          </a>
        ';
      }
    }

    ?>
  </div>
</div>