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
$sheet->setCellValue('A1', 'RUC');
$sheet->setCellValue('B1', 'NOMBRE');
$sheet->setCellValue('C1', 'DIRECCIÓN');
$sheet->setCellValue('D1', 'TELÉFONO');
$sheet->setCellValue('E1', 'FECHA DE ADICIÓN');

$requestFetch = true;
include './../Controllers/SupplierController.php';

$IS = new SupplierController();
$suppliers = json_decode($IS->getSuppliersController());

// Llena los datos de los supplieres en las filas
$row = 2;
foreach ($suppliers as $supplier) {
  // colocando valores a celdas
  $sheet->setCellValue('A' . $row, $supplier->RUC);
  $sheet->setCellValue('B' . $row, $supplier->name);
  $sheet->setCellValue('C' . $row, $supplier->address);
  $sheet->setCellValue('D' . $row, $supplier->phone);
  $sheet->setCellValue('E' . $row, date("d/m/Y", strtotime($supplier->created_at)));
  $row++;
}
// Crea una instancia del objeto Xlsx Writer
$writer = new Xlsx($spreadsheet);

// Establece el tipo de contenido y las cabeceras para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=reporte_proveedores.xlsx');
header('Cache-Control: max-age=0');

// Guarda el archivo Excel en el flujo de salida
$writer->save('php://output');
exit;
