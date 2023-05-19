<?php

require_once "MainModel.php";

class CartModel extends MainModel
{

  protected static function addItemModel(int $product_id, string $name, float $price, int $quantity): bool
  {
    $item = [
      "product_id" => $product_id,
      "name" => $name,
      "quantity" => $quantity,
      "price" => $price,
      "total" => $quantity * $price,
    ];
    $lenght_before = count($_SESSION['cart']['items']);
    $lenght_after = array_push($_SESSION['cart']['items'], $item);

    return ($lenght_before + 1 == $lenght_after);
  }

  protected static function updateQuantityModel(int $id, int $new_quantity)
  {
    if (isset($_SESSION['cart']['items'][$id])) {
      $_SESSION['cart']['items'][$id]->quantity = $new_quantity;
    }
  }

  protected static function removeItemModel(int $product_id): bool
  {
    $lenght_before = count($_SESSION['cart']['items']);
    $items_update = array_filter($_SESSION['cart']['items'], function ($item) use ($product_id) {
      return $item['product_id'] != $product_id;
    });

    $_SESSION['cart']['items'] = $items_update;
    if (count($_SESSION['cart']['items']) < 1)  $_SESSION['cart']['discount'] = 0.00;

    $lenght_after = count($_SESSION['cart']['items']);

    return  $lenght_before == $lenght_after + 1;
  }

  protected static function applyDiscountModel(float $discount): bool
  {
    $_SESSION['cart']['discount'] = $discount;
    return $_SESSION['cart']['discount'] == $discount;
  }

  protected static function getItemsModel(): array
  {
    return array(...$_SESSION['cart']['items']);
  }

  protected static function getTotalModel(): float
  {
    $total = 0;
    foreach ($_SESSION['cart']['items'] as $item) {
      $total += $item['total'];
    }
    return $total;
  }

  public function getCountModel(): int
  {
    return count($_SESSION['cart']['items']);
  }

  public function clearModel()
  {
    $_SESSION['cart']['items'] = array();
    $_SESSION['cart']['discount'] = 0.00;
  }
}
