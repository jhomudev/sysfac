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

$request = true;
include './../Controllers/UserController.php';

$IU = new UserController();
$users = json_decode($IU->getUsersController());

// Crea una instancia del objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Establece los encabezados de las columnas
$sheet->setCellValue('A1', 'DNI');
$sheet->setCellValue('B1', 'NOMBRES');
$sheet->setCellValue('C1', 'APELLIDOS');
$sheet->setCellValue('D1', 'CORREO');
$sheet->setCellValue('E1', 'ACCESO');
$sheet->setCellValue('F1', 'ACTIVO');

// Llena los datos de los useres en las filas
$row = 2;
foreach ($users as $user) {
  if ($user->type == USER_TYPE->superadmin) $type = "SUPERADMIN";
  else if ($user->type == USER_TYPE->admin) $type = "ADMIN";
  else if ($user->type == USER_TYPE->vendedor) $type = "VENDEDOR";

  $is_active = $user->is_active ? "SI" : "NO";
  // colocando valores a celdas
  $sheet->setCellValue('A' . $row, $user->dni);
  $sheet->setCellValue('B' . $row, $user->names);
  $sheet->setCellValue('C' . $row, $user->lastnames);
  $sheet->setCellValue('D' . $row, $user->email);
  $sheet->setCellValue('E' . $row, $type);
  $sheet->setCellValue('F' . $row, $is_active);
  $row++;
}
// Crea una instancia del objeto Xlsx Writer
$writer = new Xlsx($spreadsheet);

// Establece el tipo de contenido y las cabeceras para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=reporte_usuarios.xlsx');
header('Cache-Control: max-age=0');

// Guarda el archivo Excel en el flujo de salida
$writer->save('php://output');
exit;
