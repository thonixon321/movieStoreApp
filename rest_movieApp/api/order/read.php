<?php

//THIS ENDPOINT READS ALL THE DATA IN ORDERS TABLE

//Headers (required for http request)
header('Access-Control-Allow-Origin: http://localhost:8082');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Order.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

//Instantiate blog post object
$order = new Order($db);

//Read order query
$result = $order->read();

$num = $result->rowCount();

if ($num > 0) {
  $orders_arr = array();

  $orders_arr['data'] = array();

  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    //pulls out all the keys from the associative array (object) and puts them and their values as a var e.g. ['order_id' => '1'] is now stored in $order_id, etc.
    extract($row);

    $order_item = array(
      'first_name' => $first_name,
      'last_name' => $last_name,
      'email' => $email,
      'order_id' => $order_id,
      'order_date' => $order_date,
      'total_price' => $total_price,
      'quantity' => $quantity,
      'name' => $name,
      'price' => $price,
      'type' => $type,
      'image' => $image,
      'movie_id' => $movie_id
    );

    array_push($orders_arr['data'], $order_item);

  }

  echo json_encode($orders_arr);
}
else{
  echo json_encode(
    array('message' => 'No orders Found')
  );
}