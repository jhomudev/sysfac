<?php

$requestFetch = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (isset($_SESSION['token'])) {
  require_once "../Controllers/ProductController.php";
  $IP = new ProductController();

  $products = json_decode($IP->getProductsController());

  $data = "";

  if ($products) {
    foreach ($products as $key => $product) {
      $data .= '
      <article class="product">
        <div class="product__imgBox">
          <img src="https://cdn-icons-png.flaticon.com/512/10810/10810368.png" loading="lazy" alt="product box" class="product__img">
        </div>
        <p class="product_name">' . $product->name . '</p>
        <span class="product_price">S/' . $product->price_sale . '</span>
        <button class="product__form__submit toggleForm" type="submit" data-id="' . $product->product_id . '"><i class="ph ph-shopping-cart-simple"></i></button>
      </article>
      ';
    }
  } else {
    $data .= '
    <div style="text-align:center;">
      <div class="empty">
        <div class="empty__imgBox"><img src="https://cdn-icons-png.flaticon.com/512/5445/5445197.png" alt="vacio" class="empty__img"></div>
      </div>
      <p class="empty__message">No hay registros</p>
    </div>
    ';
  }

  echo $data;
} else {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}
