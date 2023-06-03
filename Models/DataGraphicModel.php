<?php

require_once "MainModel.php";

class DataGraphicModel extends MainModel
{
  // Funcion de obtener ventas y compras, es por año
  protected static function getSalesAndPurchasesModel(string $year): array
  {
    $quantity_sales = [];
    $quantity_purchases = [];

    foreach (MONTHS as $key => $month) {
      $n_month = $key + 1;
      // Ventas
      $sells_q = MainModel::executeQuerySimple("SELECT COUNT(*) AS count FROM sells WHERE MONTH(created_at)=$n_month AND YEAR(created_at)=$year AND operation_type=" . OPERATION->output);
      $sells_q = $sells_q->fetchColumn();
      array_push($quantity_sales, $sells_q);

      // Compras
      $purchases_q = MainModel::executeQuerySimple("SELECT COUNT(*) AS count FROM sells WHERE MONTH(created_at)=$n_month AND YEAR(created_at)=$year AND operation_type=" . OPERATION->input);
      $purchases_q = $purchases_q->fetchColumn();
      array_push($quantity_purchases, $purchases_q);
    }

    return [
      "quantity_sales" => $quantity_sales,
      "quantity_purchases" => $quantity_purchases,
    ];
  }

  // Funcion de obtener productos x estado
  protected static function getProductsForStatesModel(): array
  {
    // en stock
    $p_stock = MainModel::executeQuerySimple("SELECT COUNT(*) AS count FROM products_all WHERE state=" . STATE_IN->stock);
    $p_stock = $p_stock->fetchColumn();

    // dañados
    $p_damaged = MainModel::executeQuerySimple("SELECT COUNT(*) AS count FROM products_all WHERE state=" . STATE_IN->damaged);
    $p_damaged = $p_damaged->fetchColumn();

    // vendidos
    $p_sold = MainModel::executeQuerySimple("SELECT COUNT(*) AS count FROM products_all WHERE state=" . STATE_IN->sold);
    $p_sold = $p_sold->fetchColumn();


    return [$p_stock, $p_damaged, $p_sold];
  }

  // !FALATA ESSTO Funcion de obtener data de productos mas vendidos
  protected static function getProductsBestModel(string $year, string $month): array
  {
    $data = MainModel::executeQuerySimple("SELECT (SELECT COUNT(*) FROM operations WHERE product_id=o.product_id) AS quantity, MAX(p.name) AS product FROM operations o INNER JOIN sells s ON s.sell_code=o.sell_code
    INNER JOIN products p ON p.product_id=o.product_id
    WHERE s.operation_type=" . OPERATION->output . " AND YEAR(s.created_at)=$year GROUP BY o.product_id LIMIT 6");

    if (!empty($year) && !empty($month)) {
      $data = MainModel::executeQuerySimple("SELECT (SELECT COUNT(*) FROM operations WHERE product_id=o.product_id) AS quantity, MAX(p.name) AS product FROM operations o INNER JOIN sells s ON s.sell_code=o.sell_code
      INNER JOIN products p ON p.product_id=o.product_id
      WHERE s.operation_type=" . OPERATION->output . " AND YEAR(s.created_at)=$year AND MONTH(s.created_at)=$month GROUP BY o.product_id LIMIT 6");
    }

    $data = $data->fetchAll();

    return $data;
  }
}
