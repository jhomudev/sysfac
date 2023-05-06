<?php

$user_type = [
  "superadmin" => 1,
  "admin" => 2,
  "vendedor" => 3,
];
$user_type = json_decode(json_encode($user_type));

define('USER_TYPE', $user_type);


$state = [
  "active" => 1,
  "inactive" => 0,
];
$state = json_decode(json_encode($state));
define("STATE", $state);
