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

    foreach ($clients as $key => $client) {
      $clients[$key]['client_id'] = $this->encryption($clients[$key]['client_id']);
    }

    return json_encode($clients);
  }

  // Funcion controlador para obtener los datos de cliente
  public function getDataClientController()
  {
    $type_proof = isset($_POST['typeProof']) ? intval($_POST['typeProof']) : "";
    $dni_ruc = isset($_POST['id_dni_ruc']) ? intval($_POST['id_dni_ruc']) : "";

    if (isset($_POST['client_id'])) {
      // SI ES X ID
      $client_id = $this->clearString($_POST['client_id']);
      // Validar campos vacios si es x id
      if (empty($client_id)) {
        $alert = [
          "Alert" => "simple",
          "title" => "Campos vacios",
          "text" => "El cliente a modificar no esta definido.",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }

      $_POST['id_dni_ruc'] = $this->decryption($client_id);
    } else {
      // SI ES POR DNI/RUC
      // validar campos vacios si es por DNI o RUC
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
    }

    $data_search = $_POST;
    $client = ClientModel::getDataClientModel($data_search);

    if ($client == false) {
      $alert = [
        "Alert" => "simple",
        "title" => "Cliente no encontrado" . implode(",", $_POST),
        "text" => "Al paracer el cliente no esta registrado.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }
    $client['client_id'] = ClientModel::encryption($client['client_id']);
    return json_encode($client);
  }

  // Funcion controlador para crear cliente
  public function createClientController()
  {
    $dni = isset($_POST['tx_client_dni']) && !empty($_POST['tx_client_dni']) ? MainModel::clearString($_POST['tx_client_dni']) : "";
    $RUC = isset($_POST['tx_client_RUC']) && !empty($_POST['tx_client_RUC']) ? MainModel::clearString($_POST['tx_client_RUC']) : "";
    $names = MainModel::clearString($_POST['tx_client_names']);
    $lastnames = MainModel::clearString($_POST['tx_client_lastnames']);
    $address = isset($_POST['tx_client_address']) ? MainModel::clearString($_POST['tx_client_address']) : "";
    $phone = isset($_POST['tx_client_phone']) ? MainModel::clearString($_POST['tx_client_phone']) : "";
    $email = isset($_POST['tx_client_email']) ? MainModel::clearString($_POST['tx_client_email']) : "";

    // Validacion de campos vacios
    if (empty($names) || empty($lastnames) || (empty($dni) && empty($RUC))) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validación de duplicidad de dni o RUC
    if (!empty($dni) && !empty($RUC)) {
      $sql_verify =  MainModel::executeQuerySimple("SELECT * FROM clients WHERE (dni=$dni OR RUC=$RUC)");
      $clients = $sql_verify->fetchAll();
    }
    if (!empty($dni) && empty($RUC)) {
      $sql_verify =  MainModel::executeQuerySimple("SELECT * FROM clients WHERE dni=$dni");
      $clients = $sql_verify->fetchAll();
    }
    if (empty($dni) && !empty($RUC)) {
      $sql_verify =  MainModel::executeQuerySimple("SELECT * FROM clients WHERE RUC=$RUC");
      $clients = $sql_verify->fetchAll();
    }

    $duplicated = count($clients) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El DNI/RUC ya está registrado a otro cliente.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $data = [
      "client_id" => uniqid("C-") . strtotime("now"),
      "dni" => $dni,
      "RUC" => $RUC,
      "names" => $names,
      "lastnames" => $lastnames,
      "address" => $address,
      "phone" => $phone,
      "email" => $email,
      "created_at" =>  date('Y-m-d H:i:s'),
    ];

    $stm = ClientModel::createClientModel($data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Cliente registrado",
        "text" => "El cliente se registró exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "No pudimos registrar al cliente.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }

  // Funcion controlador para editar cliente
  public function editClientController()
  {
    $client_id = isset($_POST['tx_client_id']) && !empty($_POST['tx_client_id']) ? MainModel::clearString($_POST['tx_client_id']) : "";
    $dni = isset($_POST['tx_client_dni']) && !empty($_POST['tx_client_dni']) ? MainModel::clearString($_POST['tx_client_dni']) : "";
    $RUC = isset($_POST['tx_client_RUC']) && !empty($_POST['tx_client_RUC']) ? MainModel::clearString($_POST['tx_client_RUC']) : "";
    $names = MainModel::clearString($_POST['tx_client_names']);
    $lastnames = MainModel::clearString($_POST['tx_client_lastnames']);
    $address = isset($_POST['tx_client_address']) ? MainModel::clearString($_POST['tx_client_address']) : "";
    $phone = isset($_POST['tx_client_phone']) ? MainModel::clearString($_POST['tx_client_phone']) : "";
    $email = isset($_POST['tx_client_email']) ? MainModel::clearString($_POST['tx_client_email']) : "";

    // Validacion de campos vacios
    if (empty($client_id) || empty($names) || empty($lastnames) || (empty($dni) && empty($RUC))) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $client_id = $this->decryption($client_id);

    // validación de duplicidad de dni o RUC
    if (!empty($dni) && !empty($RUC)) {
      $sql_verify =  MainModel::executeQuerySimple("SELECT * FROM clients WHERE client_id<>'$client_id' AND (dni=$dni OR RUC=$RUC)");
      $clients = $sql_verify->fetchAll();
    }
    if (!empty($dni) && empty($RUC)) {
      $sql_verify =  MainModel::executeQuerySimple("SELECT * FROM clients WHERE client_id<>'$client_id' AND dni=$dni");
      $clients = $sql_verify->fetchAll();
    }
    if (empty($dni) && !empty($RUC)) {
      $sql_verify =  MainModel::executeQuerySimple("SELECT * FROM clients WHERE client_id<>'$client_id' AND RUC=$RUC");
      $clients = $sql_verify->fetchAll();
    }

    $duplicated = count($clients) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El DNI/RUC ya está registrado a otro cliente.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $new_data = [
      "client_id" => $client_id,
      "dni" => $dni,
      "RUC" => $RUC,
      "names" => $names,
      "lastnames" => $lastnames,
      "address" => $address,
      "phone" => $phone,
      "email" => $email,
    ];

    $stm = ClientModel::editClientModel($new_data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Cliente actualizado",
        "text" => "Los datos del cliente se actualizaron.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "No pudimos actualizar los datos del cliente.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
}
