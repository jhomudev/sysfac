<?php
require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (empty($_SESSION['token'])) {
  session_unset();
  session_destroy();
  header("Local:" . SERVER_URL . "/login");
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
$sheet->setCellValue('B1', 'DIRECCIÓN');
$sheet->setCellValue('C1', 'TILO');
$sheet->setCellValue('D1', 'CANSTORE');

$request = true;
include './../Controllers/LocalController.php';

$IL = new LocalController();
$locals = json_decode($IL->getLocalsController());

// Llena los datos de los locales en las filas
$row = 2;
foreach ($locals as $local) {
  $canStore = $local->canStoreMore ? "SI" : "NO";
  $type = $local->type == LOCATION->store ? "TIENDA" : "ALMACÉN";

  // colocando valores a celdas
  $sheet->setCellValue('A' . $row, $local->name);
  $sheet->setCellValue('B' . $row, $local->address);
  $sheet->setCellValue('C' . $row, $type);
  $sheet->setCellValue('D' . $row, $canStore);
  $row++;
}
// Crea una instancia del objeto Xlsx Writer
$writer = new Xlsx($spreadsheet);

// Establece el tipo de contenido y las cabeceras para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=reporte_locales.xlsx');
header('Cache-Control: max-age=0');

// Guarda el archivo Excel en el flujo de salida
$writer->save('php://output');
exit;
