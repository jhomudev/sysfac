<?php

if ($requestFetch) {
  require_once "./../Models/ClientModel.php";
} else {
  require_once "./Models/ClientModel.php";
}

class ClientController extends ClientModel
{
  // Función controlador para obtener los clientes
  public function getClientsController()
  {
    $clients = ClientModel::getClientsModel($_POST['words']);
    return json_encode($clients);
  }

  // Funcion controlador para obtener los datos de cliente
  public function getDataClientController()
  {
    $type_proof = intval($_POST['typeProof']);
    $dni_ruc = intval($_POST['dni_ruc']);

    if (empty($type_proof) || empty($dni_ruc)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Elija el tipo de comprobante de pago y escriba el RUC/DNI.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }
    if (is_string($type_proof) || !(is_numeric($dni_ruc))) {
      $alert = [
        "Alert" => "simple",
        "title" => "Datos inválidos",
        "text" => "Por favor. Complete los campos correctamente.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $data_search = $_POST;
    $client = ClientModel::getDataClientModel($data_search);

    if ($client == false) {
      $alert = [
        "Alert" => "simple",
        "title" => "Cliente no encontrado",
        "text" => "Al paracer el cliente no esta registrado.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }
    $client['person_id'] = ClientModel::encryption($client['person_id']);
    return json_encode($client);
  }

  // Funcion controlador para crear o editar cliente
  public function createClientController()
  {
    $name = MainModel::clearString($_POST['tx_nombre']);
    $link_image = MainModel::clearString($_POST['tx_linkImage']);
    $description = MainModel::clearString($_POST['tx_descripcion']);

    // Validacion de campos vacios
    if (empty($name)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validación de duplicidad de nombre
    $sql_verify = MainModel::executeQuerySimple("SELECT * FROM clients WHERE name='$name'");
    $clients = $sql_verify->fetchAll();
    $duplicated = count($clients) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El nombre de cada categoría es único.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $data = [
      "link_image" => $link_image,
      "name" => $name,
      "description" => $description,
      "created_at" =>  date('Y-m-d H:i:s'),
    ];

    $stm = ClientModel::createClientModel($data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Categoría creada",
        "text" => "La categortía se creó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "La categoría no se creó.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
}
