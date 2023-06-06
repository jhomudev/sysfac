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

$requestFetch = true;
include './../Controllers/ClientController.php';

$IC = new ClientController();
$clients = json_decode($IC->getClientsController());

// Crea una instancia del objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Establece los encabezados de las columnas
$sheet->setCellValue('A1', 'DNI');
$sheet->setCellValue('B1', 'RUC');
$sheet->setCellValue('C1', 'NOMBRES');
$sheet->setCellValue('D1', 'APELLIDOS');
$sheet->setCellValue('E1', 'TELÉFONO');
$sheet->setCellValue('F1', 'CORREO');
$sheet->setCellValue('G1', 'DIRECCIÓN');

// Llena los datos de los clientes en las filas
$row = 2;
foreach ($clients as $client) {
  $dni = $client->dni ? $client->dni : "";
  $RUC = $client->RUC ? $client->RUC : "";
  $phone = $client->phone ? $client->phone : "";
  $email = $client->email ? $client->email : "";
  $adress = $client->address ? $client->address : "";

  // colocando valores a celdas
  $sheet->setCellValue('A' . $row, $dni);
  $sheet->setCellValue('B' . $row, $RUC);
  $sheet->setCellValue('C' . $row, $client->names);
  $sheet->setCellValue('D' . $row, $client->lastnames);
  $sheet->setCellValue('E' . $row, $phone);
  $sheet->setCellValue('F' . $row, $email);
  $sheet->setCellValue('G' . $row, $adress);
  $row++;
}
// Crea una instancia del objeto Xlsx Writer
$writer = new Xlsx($spreadsheet);

// Establece el tipo de contenido y las cabeceras para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=reporte_clientes.xlsx');
header('Cache-Control: max-age=0');

// Guarda el archivo Excel en el flujo de salida
$writer->save('php://output');
exit;
