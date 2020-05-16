<?php
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
//needed for axios
$_POST = json_decode(file_get_contents("php://input"), true);

if (isset($_POST['customer_id'])) {
  $customer->customer_id = $_POST['customer_id'];
}
//make sure the fields are not empty before sending
if (! empty($_POST['customer_id']) && $customer->logout()) {
  echo json_encode(
    array('message' => $_POST)
  );
}
else{
  echo json_encode(
    array('message' => 'customer not logged out')
  );
}