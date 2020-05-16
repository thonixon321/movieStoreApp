<?php

//THIS ENDPOINT UPDATES THE DATA IN TABLE

//Headers (required for http request)
header('Access-Control-Allow-Origin: http://localhost:8082');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Customer.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

$customer = new Customer($db);

//needed for axios (data package sent from front end)
$_POST = json_decode(file_get_contents("php://input"), true);
//make sure the data packet sent has all the data needed to make the query
//(id, email, password, enabled)
if (isset($_POST['email'])) {
  $customer->password = $_POST['password'];
  $customer->customer_id = $customer->getIdFromEmail($_POST['email']);
}

if (! empty($_POST['email'] && $customer->update())) {
  echo json_encode(
    ['message' => 'customer updated']
  );
}
else {
  echo json_encode(
    ['message' => 'customer not updated']
  );
}