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
// ?VENTAS
// Crea una instancia del objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet_ventas = $spreadsheet->getActiveSheet();
$sheet_ventas->setTitle('Ventas');

// Establece los encabezados de las columnas
$sheet_ventas->setCellValue('A1', 'ID_VENTA');
$sheet_ventas->setCellValue('B1', 'USUARIO');
$sheet_ventas->setCellValue('C1', 'CLIENTE');
$sheet_ventas->setCellValue('D1', 'IMPORTE TOTAL');
$sheet_ventas->setCellValue('E1', 'DESCUENTO');
$sheet_ventas->setCellValue('F1', 'TOTAL PAGADO');
$sheet_ventas->setCellValue('G1', 'FECHA');

$requestFetch = true;
include './../Controllers/SellController.php';

$IC = new SellController();
$sells = json_decode($IC->getSellsController());

// Llena los datos de los selles en las filas
$row = 2;
foreach ($sells as $sell) {
  // colocando valores a celdas
  $sheet_ventas->setCellValue('A' . $row, $sell->sell_code);
  $sheet_ventas->setCellValue('B' . $row, $sell->user);
  $sheet_ventas->setCellValue('C' . $row, $sell->client);
  $sheet_ventas->setCellValue('D' . $row, $sell->total_import);
  $sheet_ventas->setCellValue('E' . $row, $sell->discount);
  $sheet_ventas->setCellValue('F' . $row, $sell->total_pay);
  $sheet_ventas->setCellValue('G' . $row, date("d/m/Y", strtotime($sell->created_at)));
  $row++;
}

// ?OPERACIONES
$sheet_ops = $spreadsheet->createSheet();
$sheet_ops->setTitle('Detalles de venta');

// Establece los encabezados de las columnas
$sheet_ops->setCellValue('A1', 'PRODUCTO');
$sheet_ops->setCellValue('B1', 'NUMERO DE SERIE');
$sheet_ops->setCellValue('C1', 'PRECIO');
$sheet_ops->setCellValue('D1', 'CANTIDAD');
$sheet_ops->setCellValue('E1', 'IMPORTE');
$sheet_ops->setCellValue('F1', 'DETALLES');
$sheet_ops->setCellValue('G1', 'ID_VENTA');

// DATOS DE LAS OPERACIONES
$ops = MainModel::executeQuerySimple("SELECT op.sell_code,op.serial_number, op.price, op.quantity, op.import, op.details, p.name AS product FROM operations op 
INNER JOIN products p ON p.product_iD=op.product_id
INNER JOIN sells s ON s.sell_code=op.sell_code
WHERE s.operation_type = " . OPERATION->output);
$ops = json_decode(json_encode($ops->fetchAll()));


// Llena los datos de los selles en las filas
$row = 2;
foreach ($ops as $op) {
  // colocando valores a celdas
  $sheet_ops->setCellValue('A' . $row, $op->product);
  $sheet_ops->setCellValue('B' . $row, $op->serial_number);
  $sheet_ops->setCellValue('C' . $row, $op->price);
  $sheet_ops->setCellValue('D' . $row, $op->quantity);
  $sheet_ops->setCellValue('E' . $row, $op->import);
  $sheet_ops->setCellValue('F' . $row, $op->details);
  $sheet_ops->setCellValue('G' . $row, $op->sell_code);
  $row++;
}

// Crea una instancia del objeto Xlsx Writer
$writer = new Xlsx($spreadsheet);

// Establece el tipo de contenido y las cabeceras para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=reporte_ventas.xlsx');
header('Cache-Control: max-age=0');

// Guarda el archivo Excel en el flujo de salida
$writer->save('php://output');
exit;
