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
// ?COMPRAS
// Crea una instancia del objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet_compras = $spreadsheet->getActiveSheet();
$sheet_compras->setTitle('Compras');

// Establece los encabezados de las columnas
$sheet_compras->setCellValue('A1', 'ID_COMPRA');
$sheet_compras->setCellValue('B1', 'RESPONSABLE');
$sheet_compras->setCellValue('C1', 'PROVEEDOR');
$sheet_compras->setCellValue('D1', 'TOTAL');
$sheet_compras->setCellValue('E1', 'FECHA');

$requestFetch = true;
include './../Controllers/PurchaseController.php';

$IP = new PurchaseController();
$purchases = json_decode($IP->getPurchasesController());

// Llena los datos de los itemes en las filas
$row = 2;
foreach ($purchases as $item) {
  // colocando valores a celdas
  $sheet_compras->setCellValue('A' . $row, $item->sell_code);
  $sheet_compras->setCellValue('B' . $row, $item->user);
  $sheet_compras->setCellValue('C' . $row, $item->supplier);
  $sheet_compras->setCellValue('D' . $row, $item->total_pay);
  $sheet_compras->setCellValue('E' . $row, date("d/m/Y", strtotime($item->created_at)));
  $row++;
}

// ?OPERACIONES
$sheet_ops = $spreadsheet->createSheet();
$sheet_ops->setTitle('Detalles de compra');

// Establece los encabezados de las columnas
$sheet_ops->setCellValue('A1', 'PRODUCTO');
$sheet_ops->setCellValue('B1', 'NUMERO DE SERIE');
$sheet_ops->setCellValue('C1', 'PRECIO');
$sheet_ops->setCellValue('D1', 'CANTIDAD');
$sheet_ops->setCellValue('E1', 'IMPORTE');
$sheet_ops->setCellValue('F1', 'ID_COMPRA');

// DATOS DE LAS OPERACIONES
$ops = MainModel::executeQuerySimple("SELECT op.sell_code,op.serial_number, op.price, op.quantity, op.import, p.name AS product FROM operations op 
INNER JOIN products p ON p.product_iD=op.product_id
INNER JOIN sells s ON s.sell_code=op.sell_code
WHERE s.operation_type = " . OPERATION->input);
$ops = json_decode(json_encode($ops->fetchAll()));


// Llena los datos de los itemes en las filas
$row = 2;
foreach ($ops as $op) {
  // colocando valores a celdas
  $sheet_ops->setCellValue('A' . $row, $op->product);
  $sheet_ops->setCellValue('B' . $row, $op->serial_number);
  $sheet_ops->setCellValue('C' . $row, $op->price);
  $sheet_ops->setCellValue('D' . $row, $op->quantity);
  $sheet_ops->setCellValue('E' . $row, $op->import);
  $sheet_ops->setCellValue('F' . $row, $op->sell_code);
  $row++;
}

// Crea una instancia del objeto Xlsx Writer
$writer = new Xlsx($spreadsheet);

// Establece el tipo de contenido y las cabeceras para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=reporte_compras.xlsx');
header('Cache-Control: max-age=0');

// Guarda el archivo Excel en el flujo de salida
$writer->save('php://output');
exit;
