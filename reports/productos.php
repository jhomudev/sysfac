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

// Crea una instancia del objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Establece los encabezados de las columnas
$sheet->setCellValue('A1', 'NOMBRE');
$sheet->setCellValue('B1', 'MÍNIMO EN INVENTARIO');
$sheet->setCellValue('C1', 'PRECIO DE VENTA');
$sheet->setCellValue('D1', 'UNIDAD');
$sheet->setCellValue('E1', 'VENTA POR');
$sheet->setCellValue('F1', 'CATEGORÍA');
$sheet->setCellValue('G1', 'ACTIVO');

$request = true;
include './../Controllers/ProductController.php';

$IP = new ProductController();
$products = json_decode($IP->getProductsController());

// Llena los datos de los productes en las filas
$row = 2;
foreach ($products as $product) {
  $is_active = $product->is_active ? "SI" : "NO";
  $sale_for = $product->sale_for == ADD_FOR->quantity ? "CANTIDAD" : "UNIDAD/NÚMERO DE SERIE";


  // colocando valores a celdas
  $sheet->setCellValue('A' . $row, $product->name);
  $sheet->setCellValue('B' . $row, $product->inventary_min);
  $sheet->setCellValue('C' . $row, $product->price_sale);
  $sheet->setCellValue('D' . $row, $product->unit);
  $sheet->setCellValue('E' . $row, $sale_for);
  $sheet->setCellValue('F' . $row, $product->category);
  $sheet->setCellValue('G' . $row, $is_active);
  $row++;
}
// Crea una instancia del objeto Xlsx Writer
$writer = new Xlsx($spreadsheet);

// Establece el tipo de contenido y las cabeceras para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=reporte_productos.xlsx');
header('Cache-Control: max-age=0');

// Guarda el archivo Excel en el flujo de salida
$writer->save('php://output');
exit;
