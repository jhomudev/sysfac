<?php

require_once "MainModel.php";

class CartPurchaseModel extends MainModel
{

  protected static function addItemModel(int $product_id, string $serial_number, string $name, float $price, float $price_sale, int $quantity, int $profit): bool
  {
    $price_sale = $price_sale ? $price_sale : $price + ($profit / 100 * $price);
    $item = [
      "product_id" => $product_id,
      "serial_number" => $serial_number,
      "name" => $name,
      "quantity" => $quantity,
      "price" => $price,
      "price_sale" => $price_sale,
      "total" => $quantity * $price,
    ];
    $lenght_before = count($_SESSION['cart_purchase']['items']);
    $lenght_after = array_push($_SESSION['cart_purchase']['items'], $item);

    return ($lenght_before + 1 == $lenght_after);
  }

  protected static function removeItemModel(string $col, mixed $val): bool
  {
    $lenght_before = count($_SESSION['cart_purchase']['items']);
    $items_update = array_filter($_SESSION['cart_purchase']['items'], function ($item) use ($col, $val) {
      return $item[$col] != $val;
    });

    $_SESSION['cart_purchase']['items'] = $items_update;

    $lenght_after = count($_SESSION['cart_purchase']['items']);

    return  $lenght_before == $lenght_after + 1;
  }

  protected static function getItemsModel(): array
  {
    return array(...$_SESSION['cart_purchase']['items']);
  }

  protected static function getTotalModel(): float
  {
    $total = 0;
    foreach ($_SESSION['cart_purchase']['items'] as $item) {
      $total += $item['total'];
    }
    return $total;
  }

  public function getCountModel(): int
  {
    return count($_SESSION['cart_purchase']['items']);
  }

  public function clearModel()
  {
    $_SESSION['cart_purchase']['items'] = array();
  }
}
