<?php

require_once "MainModel.php";

class SellModel extends MainModel
{

  // Funcion de obtener ventas
  protected static function getSellsModel(array $filters): array
  {
    $words = $filters['words'];
    $column = $filters['column'];
    $value = $filters['value'];
    $date_start = $filters['date_start'];
    $date_end = $filters['date_end'];
    $date_start = (!empty($date_start) ? date('Y-m-d', strtotime($date_start)) : "");
    $date_end = (!empty($date_end) ? date('Y-m-d', strtotime($date_end)) : "");

    $operation_type = OPERATION->output;

    if (empty($words) && empty($column) && empty($value) && empty($date_start) && empty($date_end)) $sells = MainModel::connect()->prepare("SELECT s.sell_code, s.proof_code,s.proof_type,s.discount,s.total_import,s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, CONCAT(p.names,' ',p.lastnames) AS client FROM sells s INNER JOIN persons p ON s.person_id = p.person_id 
    INNER JOIN users u ON u.user_id=s.user_id 
    WHERE s.operation_type=$operation_type ORDER by s.sell_id DESC");
    else {
      if (!empty($words)) {
        $words = "%$words%";
        $sells = MainModel::connect()->prepare("SELECT s.sell_code, s.proof_code,s.proof_type,s.discount,s.total_import,s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, CONCAT(p.names,' ',p.lastnames) AS client FROM sells s INNER JOIN persons p ON s.person_id = p.person_id 
        INNER JOIN users u ON u.user_id=s.user_id 
        WHERE s.operation_type=$operation_type AND (CONCAT(p.names,' ',p.lastnames) LIKE :words OR s.proof_code LIKE :words) ORDER by s.sell_id DESC");
        $sells->bindParam(":words", $words, PDO::PARAM_STR);
      }

      if (!empty($date_end) || !empty($date_start)) {
        $sells = MainModel::connect()->prepare("SELECT s.sell_code, s.proof_code,s.proof_type,s.discount,s.total_import,s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, CONCAT(p.names,' ',p.lastnames) AS client FROM sells s INNER JOIN persons p ON s.person_id = p.person_id 
        INNER JOIN users u ON u.user_id=s.user_id 
        WHERE s.operation_type=$operation_type AND s.created_at BETWEEN :date_start AND DATE_ADD(:date_end, INTERVAL 1 DAY) ORDER by s.sell_id DESC");
        $sells->bindParam(":date_start", $date_start, PDO::PARAM_STR);
        $sells->bindParam(":date_end", $date_end, PDO::PARAM_STR);
      }

      if (!empty($column) && !empty($value)) {
        $sells = MainModel::connect()->prepare("SELECT s.sell_code, s.proof_code,s.proof_type,s.discount,s.total_import,s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, CONCAT(p.names,' ',p.lastnames) AS client FROM sells s INNER JOIN persons p ON s.person_id = p.person_id 
        INNER JOIN users u ON u.user_id=s.user_id 
        WHERE s.operation_type=" . $operation_type . " AND u.$column=:value ORDER by s.sell_id DESC");
        $sells->bindParam(":value", $value, PDO::PARAM_STR);
      }
    }

    $sells->execute();

    return $sells->fetchAll();


  }

  protected static function getDataSellModel(string $proof_code): array
  {
    // DATOS DEL SELL
    $sell = MainModel::connect()->prepare("SELECT s.sell_code, s.proof_code,s.proof_type,s.discount,s.total_import,s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, CONCAT(p.names,' ',p.lastnames) AS client, p.dni, p.RUC FROM sells s INNER JOIN persons p ON s.person_id = p.person_id 
    INNER JOIN users u ON u.user_id=s.user_id 
    WHERE s.proof_code=:proof_code");
    $sell->bindParam(":proof_code", $proof_code, PDO::PARAM_STR);
    $sell->execute();

    if ($sell->rowCount() > 0) {
      $sell_arr['data'] = $sell->fetch();

      // DATOS DE LAS OPERACIONES
      $ops = MainModel::executeQuerySimple("SELECT * FROM operations WHERE sell_code='" . $sell_arr['data']['sell_code'] . "'");
      $ops = $ops->fetchAll();

      $sell_arr['ops'] = $ops;
    } else {
      $sell_arr = [];
    }


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
        operations(product_id,serial_number,price, quantity, import,details, sell_code)
        VALUES(:product_id,:serial_number, :price, :quantity, :import,:details, :sell_code)");

        $stm_op->bindParam(":product_id", $item['product_id'], PDO::PARAM_INT);
        $stm_op->bindParam(":serial_number", $item['serial_number'], PDO::PARAM_STR);
        $stm_op->bindParam(":price", $item['price'], PDO::PARAM_STR);
        $stm_op->bindParam(":quantity", $item['quantity'], PDO::PARAM_INT);
        $stm_op->bindParam(":import", $item['total'], PDO::PARAM_STR);
        $stm_op->bindParam(":details", $item['details'], PDO::PARAM_STR);
        $stm_op->bindParam(":sell_code", $sell_data['sell_code'], PDO::PARAM_STR);

        $OK = $stm_op->execute();
      }

      return $OK;
    }
  }
}
