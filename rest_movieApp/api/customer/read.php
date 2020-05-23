<?php
header('Access-Control-Allow-Origin: http://localhost:8082');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
include_once '../../config/Database.php';
include_once '../../models/Customer.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

//Instantiate blog post object
$customer = new Customer($db);

//Read customer query
$result = $customer->read();

$num = $result->rowCount();
//if the query gave a response with at least one row of data
if ($num > 0) {
  $customers_arr = array();

  $customers_arr['data'] = array();

  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    //pulls out all the keys from the associative array (object) and puts them and their values as a var e.g. ['customer_id' => '1'] is now stored in $customer_id, etc.
    extract($row);

    $customer_item = array(
      'customer_id' => $customer_id,
      'first_name' => $first_name,
      'last_name' => $last_name
    );

    array_push($customers_arr['data'], $customer_item);

  }

  echo json_encode($customers_arr);
}
else{
  echo json_encode(
    array('message' => 'No Customers Found')
  );
}