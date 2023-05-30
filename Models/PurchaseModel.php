<?php

require_once "MainModel.php";
class PurchaseModel extends MainModel
{
  // Funcion de obtener compras
  protected static function getPurchasesModel(array $filters): array
  {
    $column = $filters['column'];
    $value = $filters['value'];
    $start_date = $filters['start_date'];
    $end_date = $filters['end_date'];

    $query = "SELECT s.sell_id, s.sell_code, s.discount, s.total_import, s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, sup.name AS supplier FROM sells s INNER JOIN users u ON u.user_id=s.user_id INNER JOIN suppliers sup ON sup.supplier_id=s.supplier_id WHERE s.operation_type=" . OPERATION->input . " ORDER BY s.sell_id DESC";
    $purchases = MainModel::connect()->prepare($query);

    if (!empty($column) && !empty($value)) {
      $query = "SELECT s.sell_id, s.sell_code, s.discount, s.total_import, s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, sup.name AS supplier FROM sells s INNER JOIN users u ON u.user_id=s.user_id INNER JOIN suppliers sup ON sup.supplier_id=s.supplier_id WHERE s.$column=:value AND s.operation_type=" . OPERATION->input." ORDER BY s.sell_id DESC";
      $purchases = MainModel::connect()->prepare($query);
      $purchases->bindParam(":value", $value);
    }
    if (!empty($start_date) && !empty($end_date)) {
      $query = "SELECT s.sell_id, s.sell_code, s.discount, s.total_import, s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, sup.name AS supplier FROM sells s INNER JOIN users u ON u.user_id=s.user_id INNER JOIN suppliers sup ON sup.supplier_id=s.supplier_id  WHERE (s.created_at BETWEEN :start_date AND DATE_ADD(:end_date, INTERVAL 1 DAY)) AND s.operation_type=" . OPERATION->input." ORDER BY s.sell_id DESC";
      $purchases = MainModel::connect()->prepare($query);
      $purchases->bindParam(":start_date", $start_date);
      $purchases->bindParam(":end_date", $end_date);
    }

    $purchases->execute();

    return $purchases->fetchAll();
  }

  // Funcion de obtener datos de una compra
  protected static function getDataPurchaseModel(string $purchase_code)
  {
    // Obteniendo ls datos de la compra compra
    $query_pur = "SELECT s.total_pay AS total, s.created_at, s.additional_info, CONCAT(u.names,' ',u.lastnames) AS user , sup.name AS supplier 
    FROM sells s INNER JOIN suppliers sup ON s.supplier_id=sup.supplier_id 
    INNER JOIN users u ON u.user_id=s.user_id 
    WHERE sell_code=:purchase_code";
    $purchase = MainModel::connect()->prepare($query_pur);
    $purchase->bindParam(":purchase_code", $purchase_code);

    $purchase->execute();


    if ($purchase->rowCount() > 0) {
      $data['data'] = $purchase->fetch();

      // DATOS DE LAS OPERACIONES
      $query_op = "SELECT o.op_id, o.serial_number, o.price, o.import, o.quantity,p.name AS product FROM operations o 
      INNER JOIN sells s ON o.sell_code = s.sell_code
      INNER JOIN products p ON o.product_id = p.product_id
      WHERE o.sell_code=:purchase_code";
      $operations = MainModel::connect()->prepare($query_op);
      $operations->bindParam(":purchase_code", $purchase_code);

      $operations->execute();
      $operations = $operations->fetchAll();

      $data['operations'] = $operations;
    } else {
      $data = [];
    }

    return $data;
  }

  // Funcion para generar compra
  protected static function generatePurchaseModel(array $data): bool
  {
    $cart_items = $_SESSION['cart_purchase']['items'];

    // Insercion a tabla sells
    $stm_purchase = MainModel::connect()->prepare("INSERT INTO 
    sells(sell_code,supplier_id, user_id, operation_type,  total_pay, additional_info, created_at)
    VALUES(:sell_code,:supplier_id, :user_id, :operation_type, :total_pay, :additional_info,:created_at)");

    $type_op = OPERATION->input;

    $stm_purchase->bindParam(":sell_code", $data['purchase_code'], PDO::PARAM_STR);
    $stm_purchase->bindParam(":user_id", $data['user_id'], PDO::PARAM_INT);
    $stm_purchase->bindParam(":supplier_id", $data['supplier_id'], PDO::PARAM_INT);
    $stm_purchase->bindParam(":operation_type", $type_op, PDO::PARAM_BOOL);
    $stm_purchase->bindParam(":total_pay", $data['total'], PDO::PARAM_STR);
    $stm_purchase->bindParam(":additional_info", $data['additional_info'], PDO::PARAM_STR);
    $stm_purchase->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);

    if ($stm_purchase->execute()) {
      // Insercion a tabla operations
      foreach ($cart_items as $item) {
        $stm_op = MainModel::connect()->prepare("INSERT INTO 
        operations(product_id,serial_number,price, quantity, import, sell_code)
        VALUES(:product_id,:serial_number, :price, :quantity, :import, :sell_code)");

        $stm_op->bindParam(":product_id", $item['product_id'], PDO::PARAM_INT);
        $stm_op->bindParam(":serial_number", $item['serial_number'], PDO::PARAM_STR);
        $stm_op->bindParam(":price", $item['price'], PDO::PARAM_STR);
        $stm_op->bindParam(":quantity", $item['quantity'], PDO::PARAM_INT);
        $stm_op->bindParam(":import", $item['total'], PDO::PARAM_STR);
        $stm_op->bindParam(":sell_code", $data['purchase_code'], PDO::PARAM_STR);

        $OK = $stm_op->execute();
      }

      return $OK;
    }
  }
}
