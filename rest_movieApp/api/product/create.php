<?php

//THIS ENDPOINT CREATES THE DATA IN TABLE

//Headers (required for http request)
header('Access-Control-Allow-Origin: *');
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
if (isset($_POST['name'])) {
  $product->name = $_POST['name'];
  $product->price = $_POST['price'];
  $product->genre = $_POST['genre'];
  $product->product_type = $_POST['product_type'];
  $product->description = $_POST['description'];
  $product->quantity_in_stock = $_POST['quantity_in_stock'];
}

if ($product->create()) {
  echo json_encode(
    array('message' => 'product created')
  );
}
else{
  echo json_encode(
    array('message' => 'product not created')
  );
}