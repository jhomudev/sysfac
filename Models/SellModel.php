<?php

require_once "MainModel.php";

class SellModel extends MainModel
{

  // Funcion de obtener ventas
  protected static function getSellsModel(array $filters = []): array
  {
    $words = $filters['words'];
    $column = $filters['column'];
    $value = $filters['value'];

    if (empty($words) && empty($column) && empty($value)) $sells = MainModel::connect()->prepare("SELECT s.sell_id,s.person_id,s.user_id, s.discount,s.total, s.fecha FROM sells s INNER JOIN persons p ON p.person_id=s.person_id INNER JOIN users u ON u.user_id=s.user_id WHERE s.operation_type=" . OPERATION->output . " ORDER by s.created_at DESC");
    else {
      if (!empty($words)) {
        $words = "%$words%";
        $sells = MainModel::connect()->prepare("SELECT p.sell_id,p.link_image,p.name, p.price_sell,p.unit,p.category_id,p.is_active,c.name AS category FROM sells p INNER JOIN categories c ON p.category_id = c.cat_id WHERE p.name LIKE :words ORDER by p.sell_id DESC");
        $sells->bindParam(":words", $words, PDO::PARAM_STR);
      };
      if (!empty($column) && isset($value)) {
        $sells = MainModel::connect()->prepare("SELECT p.sell_id,p.link_image,p.name, p.price_sell,p.unit,p.category_id,p.is_active,c.name AS category FROM sells p INNER JOIN categories c ON p.category_id = c.cat_id WHERE p.$column=:value ORDER by p.sell_id DESC");
        $sells->bindParam(":value", $value, PDO::PARAM_STR);
      }
    }

    $sells->execute();

    return $sells->fetchAll();
  }

  // Funcion para generar venta
  protected static function generateSellModel(array $sell_data, array $cart): bool
  {
    // Insercion a tabla operations
    foreach ($cart as $key => $product) {
      $statement = MainModel::connect()->prepare("INSERT INTO 
    operations(product_id, quantity, sell_id)
    VALUES(:product_id, :quantity, :sell_id)");

      $statement->bindParam(":product_id", $product['product_id'], PDO::PARAM_INT);
      $statement->bindParam(":quantity", $product['quantity'], PDO::PARAM_INT);
      $statement->bindParam(":sell_id", $product['sell_id'], PDO::PARAM_STR);
    }

    // Insercion a tabla sells
    $statement = MainModel::connect()->prepare("INSERT INTO 
    sells(sell_id, person_id, user_id, operation_type, discount, total, created_at)
    VALUES(:sell_id, :person_id, :user_id, :operation_type, :discount, :total, :created_at)");

    $statement->bindParam(":sell_id", $sell_data['sell_id'], PDO::PARAM_STR);
    $statement->bindParam(":person_id", $sell_data['person_id'], PDO::PARAM_INT);
    $statement->bindParam(":operation_type", $sell_data['operation_type'], PDO::PARAM_BOOL);
    $statement->bindParam(":discount", $sell_data['discount'], PDO::PARAM_INT);
    $statement->bindParam(":total", $sell_data['total'], PDO::PARAM_INT);
    $statement->bindParam(":created_at", $sell_data['created_at'], PDO::PARAM_STR);

    return $statement->execute();
  }
}
