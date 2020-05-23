<?php
header('Access-Control-Allow-Origin: http://localhost:8082');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
include_once '../../config/Database.php';
include_once '../../models/Customer.php';
$database = new Database();
$db = $database->connect();

$customer = new Customer($db);

$login = false;

try {
  $login = $customer->sessionLogin();
}
catch (Exception $e) {
  echo $e->getMessage();
  die();
}

$userArr;

if ($login) {
  $userArr = array(
    'message' => 'auth success',
    'email' => $customer->getEmail(),
    'customer_id' => $customer->getId(),
    'name' => $customer->getName()
  );

  echo json_encode($userArr);

}
else {
  $userArr = array(
    'message' => 'auth fail',
  );

  echo json_encode($userArr);
}