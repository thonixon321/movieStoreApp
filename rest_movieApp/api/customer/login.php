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

if (isset($_POST['email'])) {
  $customer->email = $_POST['email'];
  $customer->password = $_POST['password'];
}
//make sure the fields are not empty before sending
if (! empty($_POST['email']) && $customer->login()) {
  echo json_encode(
    array(
      'message' => 'logged in successfully',
      'email' => $customer->getEmail(),
      'customer_id' => $customer->getId()
    )
  );
}
else{
  echo json_encode(
    array('message' => 'did not log in')
  );
}