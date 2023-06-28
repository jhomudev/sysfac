<?php

if ($request) {
  require_once "./../Models/ClientModel.php";
} else {
  require_once "./Models/ClientModel.php";
}

class ClientController extends ClientModel
{
  // Función controlador para obtener los clientes
  public function getClientsController()
  {
    $words = mainmodel::getCleanPostValue('words');
    $clients = ClientModel::getClientsModel($words);

    foreach ($clients as $key => $client) {
      $clients[$key]['client_id'] = $this->encryption($clients[$key]['client_id']);
    }

    return json_encode($clients);
  }

  // Funcion controlador para obtener los datos de cliente
  public function getDataClientController()
  {
    $client_id = MainModel::decryption(MainModel::getCleanPostValue('client_id'));

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

    $client = ClientModel::getDataClientModel($client_id);

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

    $client['client_id'] = ClientModel::encryption($client['client_id']);

    return json_encode($client);
  }
  // Funcion controlador para obtener los datos de persona con API
  public function getDataPersonByIdController()
  {
    $ruc_dni = MainModel::getCleanPostValue('ruc_dni');
    $type_proof = MainModel::getCleanPostValue('typeProof');

    if (empty($type_proof)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Tipo de comprobante no definido",
        "text" => "Seleccione el tipo de comprobante a generar.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    if (empty($ruc_dni)) {
      $alert = [
        "Alert" => "simple",
        "title" => "RUC o DNI no definido",
        "text" => "Escriba el RUc o DNI del cliente, para obtener sus datos.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    if ($type_proof == TYPE_PROOF->boleta) {
      if (strlen($ruc_dni) != 8) {
        $alert = [
          "Alert" => "simple",
          "title" => "DNI inválido",
          "text" => "Usted generará una boleta. Ingrese un DNI válido.",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }
    } else if ($type_proof == TYPE_PROOF->factura) {
      if (strlen($ruc_dni) != 11) {
        $alert = [
          "Alert" => "simple",
          "title" => "RUC inválido",
          "text" => "Usted generará una factura. Ingrese un RUC válido.",
          "icon" => "warning"
        ];
        return json_encode($alert);
        exit();
      }
    }

    $data = ClientModel::getDataPersonByIdModel($ruc_dni);

    if ($data == false) {
      $alert = [
        "Alert" => "simple",
        "title" => "Persona no encontrada",
        "text" => "Al paracer el RUC o DNI no existe.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    return json_encode($data);
  }

  // Funcion controlador para crear cliente
  public function createClientController(string $dni = null, string $RUC = null)
  {
    // Validacion de campos vacios
    if (empty($dni) && empty($RUC)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Defina el RUC o DNI.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $person = $dni ? MainModel::consultDNIRUC($dni) : MainModel::consultDNIRUC($RUC);
    $names = isset($person->nombres) ? $person->nombres : $person->nombre;
    $lastname_p = $person->apellidoPaterno ?? '';
    $lastname_m = $person->apellidoMaterno ?? '';
    $data = [
      "client_id" => uniqid("C-") . strtotime("now"),
      "dni" => $dni ?? "",
      "RUC" => $RUC ?? "",
      "names" => $names,
      "lastnames" =>  $lastname_p . ' ' . $lastname_m,
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
    $client_id = MainModel::decryption(MainModel::getCleanPOSTValue('tx_client_id'));
    $address = MainModel::getCleanPostValue('tx_client_address');
    $phone = MainModel::getCleanPostValue('tx_client_phone');
    $email = MainModel::getCleanPostValue('tx_client_email');

    $new_data = [
      "client_id" => $client_id,
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
