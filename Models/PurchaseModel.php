<?php

require_once "MainModel.php";
//! MODELO AUN INCOMPLETO
class PurchaseModel extends MainModel
{
  // Funcion de obtener compras
  protected static function getPurchasesModel(array $filters): array
  {
    // $filters = [
    //   "column" => "category_id",
    //   "value" => 1,
    //   "start_date" => "13-02-2021",
    //   "end_date" => "13-02-2021",
    // ];
    $column = $filters['column'];
    $value = $filters['value'];
    $start_date = $filters['start_date'];
    $end_date = $filters['end_date'];

    if (!empty($column) && !empty($value)) {
      $query = "SELECT s.sell_id, s.discount, s.total_import, s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, sup.name AS supplier FROM sells s INNER JOIN users u ON u.user_id=s.user_id INNER JOIN persons p ON p.person_id=s.person_id INNER JOIN suppliers sup ON sup.supplier_id=p.supplier_id WHERE s.$column=:value AND s.operation_type=" . OPERATION->input;
      $purchases = MainModel::connect()->prepare($query);
      $purchases->bindParam(":value", $value);
    }
    if (!empty($start_date) && !empty($end_date)) {
      $query = "SELECT s.sell_id, s.discount, s.total_import, s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, sup.name AS supplier FROM sells s INNER JOIN users u ON u.user_id=s.user_id INNER JOIN persons p ON p.person_id=s.person_id INNER JOIN suppliers sup ON sup.supplier_id=p.supplier_id  WHERE (s.created_at BETWEEN :start_date AND :end_date) AND s.operation_type=" . OPERATION->input;
      $purchases = MainModel::connect()->prepare($query);
      $purchases->bindParam(":start_date", $start_date);
      $purchases->bindParam(":end_date", $end_date);
    } else {
      $query = "SELECT s.sell_id, s.discount, s.total_import, s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user, sup.name AS supplier FROM sells s INNER JOIN users u ON u.user_id=s.user_id INNER JOIN persons p ON p.person_id=s.person_id INNER JOIN suppliers sup ON sup.supplier_id=p.supplier_id WHERE s.operation_type=" . OPERATION->input;
      $purchases = MainModel::connect()->prepare($query);
    }

    $purchases->execute();

    return $purchases->fetchAll();
  }

  // Funcion de obtener datos de una compra
  protected static function getDataPurchaseModel(string $purchase_id)
  {
    // Obteniendo ls datos de la compra compra
    $query_pur = "SELECT * FROM sells WHERE sell_id=:purchase_id";
    $purchase = MainModel::connect()->prepare($query_pur);
    $purchase->bindParam(":purchase_id", $purchase_id);

    $purchase->execute();

    // Obteniendo las operaciones concernientes a la compra
    $query_op = "SELECT o.op_id,o.quantity,p.name FROM operations o 
    INNER JOIN sells s ON o.sell_id = s.sell_id
    INNER JOIN products p ON o.product_id = p.product_id
    WHERE o.sell_id=:purchase_id";
    $operations = MainModel::connect()->prepare($query_op);
    $operations->bindParam(":purchase_id", $purchase_id);

    $operations->execute();

    $data = [
      "purchase" => $purchase->fetch(),
      "operations" => $operations->fetchAll(),
    ];

    return $data;
  }
}
