<?php

if ($requestFetch) {
  require_once "./../Models/PersonModel.php";
} else {
  require_once "./Models/PersonModel.php";
}

class PersonController extends PersonModel
{
  // Función controlador para obtener los persona suppliers
  public function getPersonsController()
  {
    $persons = PersonModel::getPersonsModel();
    return json_encode($persons);
  }

  // Funcion controlador para obtener los datos de persona supplier
  public function getDataPersonController()
  {
    $dni = intval($_POST['person_dni']);

    $person = PersonModel::getDataPersonModel($dni);

    if ($person == false) {
      $alert = [
        "Alert" => "simple",
        "title" => "Persona no encontrada",
        "text" => "Al paracer la persona supplier no esta registrado. Puede registrar todos los datos si desea agregarla.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $person['person_id'] = PersonModel::encryption($person['person_id']);

    return json_encode($person);
  }

  // Funcion controlador para crear persona supplier
  public function createPersonController()
  {
    $dni = !empty($_POST['tx_person_dni']) ? MainModel::clearString($_POST['tx_person_dni']) : "";
    $names = MainModel::clearString($_POST['tx_person_names']);
    $lastnames = MainModel::clearString($_POST['tx_person_lastnames']);
    $address = isset($_POST['tx_person_address']) ? MainModel::clearString($_POST['tx_person_address']) : "";
    $phone = isset($_POST['tx_person_phone']) ? MainModel::clearString($_POST['tx_person_phone']) : "";
    $email = isset($_POST['tx_person_email']) ? MainModel::clearString($_POST['tx_person_email']) : "";

    // Validacion de campos vacios
    if (empty($names) || empty($lastnames || empty($dni))) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos de la persona.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validación de duplicidad de dni o RUC
    $sql_verify =  MainModel::executeQuerySimple("SELECT * FROM persons WHERE kind=" . PERSON_TYPE->supplier . " AND dni=$dni");
    $persons = $sql_verify->fetchAll();


    $duplicated = count($persons) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicid ya está registrado a otro persona.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $data = [
      "person_id" => uniqid("P-") . strtotime("now"),
      "dni" => $dni,
      "names" => $names,
      "lastnames" => $lastnames,
      "address" => $address,
      "phone" => $phone,
      "email" => $email,
      "created_at" =>  date('Y-m-d H:i:s'),
    ];

    $stm = PersonModel::createPersonModel($data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Persona registrada",
        "text" => "El persona se registró exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "No pudimos registrar la persona.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
}
