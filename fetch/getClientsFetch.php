<?php

$requestFetch = true;

require_once "../config/APP.php";

session_name(NAMESESSION);
session_start();

if (empty($_SESSION['token'])) {
  session_unset();
  session_destroy();
  header("Location:" . SERVER_URL . "/login");
  exit();
}

require_once "../Models/MainModel.php";

if (empty($_POST['words'])) $clients = MainModel::executeQuerySimple("SELECT * FROM persons WHERE kind=" . PERSON_TYPE->client . "");
else {
  $words = $_POST['words'];
  $clients = MainModel::executeQuerySimple("SELECT * FROM persons WHERE (CONCAT(names,' ' ,lastnames) LIKE '%$words%' OR dni LIKE '%$words%' OR RUC LIKE '%$words%') AND kind=" . PERSON_TYPE->client . "");
}

$clients = $clients->fetchAll();

echo json_encode($clients);
