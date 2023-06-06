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
$sheet->setCellValue('B1', 'DESCRIPCIÓN');

$requestFetch = true;
include './../Controllers/CategoryController.php';

$IC = new CategoryController();
$categories = json_decode($IC->getCategoriesController());

// Llena los datos de los categoryes en las filas
$row = 2;
foreach ($categories as $key => $category) {
  // colocando valores a celdas
  $sheet->setCellValue('A' . $row, sprintf("%02d", $key + 1));
  $sheet->setCellValue('B' . $row, $category->name);
  $row++;
}
// Crea una instancia del objeto Xlsx Writer
$writer = new Xlsx($spreadsheet);

// Establece el tipo de contenido y las cabeceras para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=reporte_categorias.xlsx');
header('Cache-Control: max-age=0');

// Guarda el archivo Excel en el flujo de salida
$writer->save('php://output');
exit;
