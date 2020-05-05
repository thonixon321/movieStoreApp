<?php

//THIS ENDPOINT CREATES THE DATA IN TABLE

//Headers (required for http request)
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Customer.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

$customer = new Customer($db);
//needed for axios
$_POST = json_decode(file_get_contents("php://input"), true);

if (isset($_POST['email'])) {
  $customer->first_name = $_POST['first_name'];
  $customer->last_name = $_POST['last_name'];
  $customer->email = $_POST['email'];
  $customer->password = $_POST['password'];
}

if (! empty($_POST['email']) && $customer->create()) {
  echo json_encode(
    array('message' => $_POST)
  );
}
else{
  echo json_encode(
    array('message' => 'customer not created')
  );
}