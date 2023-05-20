<?php

require_once "MainModel.php";

class SellModel extends MainModel
{

  // Funcion de obtener ventas
  protected static function getSellsModel(array $filters = []): array
  {
    // $words = $filters['words'];
    // $column = $filters['column'];
    // $value = $filters['value'];

    // if (empty($words) && empty($column) && empty($value)) $sells = MainModel::connect()->prepare("SELECT s.sell_id,s.person_id,s.user_id, s.discount,s.total, s.fecha FROM sells s INNER JOIN persons p ON p.person_id=s.person_id INNER JOIN users u ON u.user_id=s.user_id WHERE s.operation_type=" . OPERATION->output . " ORDER by s.created_at DESC");
    // else {
    //   if (!empty($words)) {
    //     $words = "%$words%";
    //     $sells = MainModel::connect()->prepare("SELECT p.sell_id,p.link_image,p.name, p.price_sell,p.unit,p.category_id,p.is_active,c.name AS category FROM sells p INNER JOIN categories c ON p.category_id = c.cat_id WHERE p.name LIKE :words ORDER by p.sell_id DESC");
    //     $sells->bindParam(":words", $words, PDO::PARAM_STR);
    //   };
    //   if (!empty($column) && isset($value)) {
    //     $sells = MainModel::connect()->prepare("SELECT p.sell_id,p.link_image,p.name, p.price_sell,p.unit,p.category_id,p.is_active,c.name AS category FROM sells p INNER JOIN categories c ON p.category_id = c.cat_id WHERE p.$column=:value ORDER by p.sell_id DESC");
    //     $sells->bindParam(":value", $value, PDO::PARAM_STR);
    //   }
    // }

    $sells = MainModel::connect()->prepare("SELECT s.sell_code, s.proof_code,s.proof_type,s.discount,s.total_import,s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, CONCAT(p.names,' ',p.lastnames) AS client FROM sells s INNER JOIN persons p ON s.person_id = p.person_id 
    INNER JOIN users u ON u.user_id=s.user_id 
    WHERE s.operation_type=1 ORDER by s.sell_id DESC");
    $sells->execute();

    return $sells->fetchAll();
  }

  protected static function getDataSellModel(string $proof_code): array
  {
    // DATOS DEL SELL
    $sell = MainModel::connect()->prepare("SELECT s.sell_code, s.proof_code,s.proof_type,s.discount,s.total_import,s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, CONCAT(p.names,' ',p.lastnames) AS client, p.dni FROM sells s INNER JOIN persons p ON s.person_id = p.person_id 
    INNER JOIN users u ON u.user_id=s.user_id 
    WHERE s.proof_code=:proof_code");
    $sell->bindParam(":proof_code", $proof_code, PDO::PARAM_STR);
    $sell->execute();

    $sell_arr = $sell->fetch();

    // DATOS DE LAS OPERACIONES
    $ops = MainModel::executeQuerySimple("SELECT * FROM operations WHERE sell_code='" . $sell_arr['sell_code'] . "'");
    $ops = $ops->fetchAll();

    $sell_arr['ops'] = $ops;

    return $sell_arr;
  }

  // Funcion para generar venta
  protected static function generateSellModel(array $sell_data): bool
  {
    $cart_items = $_SESSION['cart']['items'];

    // Insercion a tabla sells
    $stm_sell = MainModel::connect()->prepare("INSERT INTO 
    sells(sell_code,proof_code, person_id, user_id, operation_type, proof_type,discount, total_import,total_pay, created_at)
    VALUES(:sell_code,:proof_code, :person_id, :user_id, :operation_type, :proof_type, :discount, :total_import,:total_pay,:created_at)");

    $type_op = OPERATION->output;

    $stm_sell->bindParam(":sell_code", $sell_data['sell_code'], PDO::PARAM_STR);
    $stm_sell->bindParam(":proof_code", $sell_data['proof_code'], PDO::PARAM_STR);
    $stm_sell->bindParam(":user_id", $sell_data['user_id'], PDO::PARAM_INT);
    $stm_sell->bindParam(":person_id", $sell_data['client_id'], PDO::PARAM_STR);
    $stm_sell->bindParam(":operation_type", $type_op, PDO::PARAM_BOOL);
    $stm_sell->bindParam(":proof_type", $sell_data['proof_type'], PDO::PARAM_INT);
    $stm_sell->bindParam(":discount", $sell_data['discount'], PDO::PARAM_STR);
    $stm_sell->bindParam(":total_import", $sell_data['total_import'], PDO::PARAM_STR);
    $stm_sell->bindParam(":total_pay", $sell_data['total_pay'], PDO::PARAM_STR);
    $stm_sell->bindParam(":created_at", $sell_data['created_at'], PDO::PARAM_STR);

    if ($stm_sell->execute()) {
      // Insercion a tabla operations
      foreach ($cart_items as $key => $item) {
        $stm_op = MainModel::connect()->prepare("INSERT INTO 
        operations(product_id,price, quantity, import, sell_code)
        VALUES(:product_id, :price, :quantity, :import, :sell_code)");

        $stm_op->bindParam(":product_id", $item['product_id'], PDO::PARAM_INT);
        $stm_op->bindParam(":price", $item['price'], PDO::PARAM_STR);
        $stm_op->bindParam(":quantity", $item['quantity'], PDO::PARAM_INT);
        $stm_op->bindParam(":import", $item['total'], PDO::PARAM_STR);
        $stm_op->bindParam(":sell_code", $sell_data['sell_code'], PDO::PARAM_STR);

        $OK = $stm_op->execute();
      }

      return $OK;
    }
  }
}
