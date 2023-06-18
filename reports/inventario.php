<?php
require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (empty($_SESSION['token'])) {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}

// Importa las clases necesarias de PhpSpreadsheet
require_once './../libraries/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// ?INVENTARIO
// Crea una instancia del objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet_inv = $spreadsheet->getActiveSheet();
$sheet_inv->setTitle('Inventario');

// Establece los encabezados de las columnas
$sheet_inv->setCellValue('A1', 'PRODUCTO');
$sheet_inv->setCellValue('B1', 'NÚMERO DE SERIE');
$sheet_inv->setCellValue('C1', 'LOCAL');
$sheet_inv->setCellValue('D1', 'ESTADO');

$request = true;
include './../Controllers/ProductController.php';

$IP = new ProductController();

$prods_inv = json_decode($IP->getProductsInventaryController());

// Llena los datos de los prodes en las filas
$row = 2;
foreach ($prods_inv as $prod) {
  // colocando valores a celdas
  $sheet_inv->setCellValue('A' . $row, $prod->product_name);
  $sheet_inv->setCellValue('B' . $row, $prod->serial_number);
  $sheet_inv->setCellValue('C' . $row, isset($prod->local_name) ? $prod->local_name : "");
  $sheet_inv->setCellValue('D' . $row, $prod->state);
  $row++;
}

// ?PRODUCTOS
$sheet_prods = $spreadsheet->createSheet();
$sheet_prods->setTitle('Productos');

// Establece los encabezados de las columnas
$sheet_prods->setCellValue('A1', 'NOMBRE');
$sheet_prods->setCellValue('B1', 'MÍNIMO EN INVENTARIO');
$sheet_prods->setCellValue('C1', 'PRECIO DE VENTA');
$sheet_prods->setCellValue('D1', 'UNIDAD');
$sheet_prods->setCellValue('E1', 'VENTA POR');
$sheet_prods->setCellValue('F1', 'CATEGORÍA');
$sheet_prods->setCellValue('G1', 'ACTIVO');

$products = json_decode($IP->getProductsController());

// Llena los datos de los productes en las filas
$row = 2;
foreach ($products as $product) {
  $is_active = $product->is_active ? "SI" : "NO";
  $sale_for = $product->sale_for == ADD_FOR->quantity ? "CANTIDAD" : "UNIDAD/NÚMERO DE SERIE";


  // colocando valores a celdas
  $sheet_prods->setCellValue('A' . $row, $product->name);
  $sheet_prods->setCellValue('B' . $row, $product->inventary_min);
  $sheet_prods->setCellValue('C' . $row, $product->price_sale);
  $sheet_prods->setCellValue('D' . $row, $product->unit);
  $sheet_prods->setCellValue('E' . $row, $sale_for);
  $sheet_prods->setCellValue('F' . $row, $product->category);
  $sheet_prods->setCellValue('G' . $row, $is_active);
  $row++;
}

// Crea una instancia del objeto Xlsx Writer
$writer = new Xlsx($spreadsheet);

// Establece el tipo de contenido y las cabeceras para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=reporte_inventario.xlsx');
header('Cache-Control: max-age=0');

// Guarda el archivo Excel en el flujo de salida
$writer->save('php://output');
exit;
