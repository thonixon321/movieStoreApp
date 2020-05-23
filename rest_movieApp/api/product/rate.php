<?php

//THIS ENDPOINT CREATES THE DATA IN TABLE

//Headers (required for http request)
header('Access-Control-Allow-Origin: http://localhost:8082');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Product.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

$product = new Product($db);
//needed for axios
$_POST = json_decode(file_get_contents("php://input"), true);

if (isset($_POST['rating']) && isset($_POST['name'])) {
  $product->rating = $_POST['rating'];
  $product->name = $_POST['name'];
  $product->message = $_POST['message'];
  $product->customer_id = $_POST['customer_id'];
}

if (! empty($_POST['rating']) && $product->rate()) {
  echo json_encode(
    array('message' => 'product rated')
  );
}
else{
  echo json_encode(
    array('message' => 'product not rated')
  );
}