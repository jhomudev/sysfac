<?php
// Tipos de usuario en la DB
$user_type = [
  "superadmin" => 1,
  "admin" => 2,
  "vendedor" => 3,
];
$user_type = json_decode(json_encode($user_type));

define('USER_TYPE', $user_type);

// Tipos de persona en la DB
$person_type = [
  "client" => 1,
  "supplier" => 0,
];
$person_type = json_decode(json_encode($person_type));

define('PERSON_TYPE', $person_type);

// Tipos de locales/locations
$locations = [
  "store" => 1,
  "warehouse" => 2,
];
$locations = json_decode(json_encode($locations));
define("LOCATION", $locations);

// Estados en el sistema
$state = [
  "active" => 1,
  "inactive" => 0,
];
$state = json_decode(json_encode($state));
define("STATE", $state);
