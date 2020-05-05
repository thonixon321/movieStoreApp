<?php

//THIS ENDPOINT CREATES THE DATA IN TABLE

//Headers (required for http request)
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Order.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

$order = new Order($db);

//needed for axios
$_POST = json_decode(file_get_contents("php://input"), true);

if (isset($_POST['customer_id'])) {

  $order->customer_id = $_POST['customer_id'];
  $order->total_price = $_POST['total_price'];
  $order->order_items = $_POST['order_items'];

}

if ($order->create()) {
  echo json_encode(
    array('message' => 'order created')
  );
}
else{
  echo json_encode(
    array('message' => 'order not created')
  );
}