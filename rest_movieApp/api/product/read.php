<?php
header('Access-Control-Allow-Origin: http://localhost:8082');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
include_once '../../config/Database.php';
include_once '../../models/Product.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

//Instantiate blog post object
$product = new Product($db);

//Read product query
$result = $product->read();

$num = $result->rowCount();

if ($num > 0) {
  $products_arr = array();

  $products_arr['data'] = array();

  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    //pulls out all the keys from the associative array (object) and puts them and their values as a var e.g. ['product_id' => '1'] is now stored in $product_id, etc.
    extract($row);

    $product_item = array(
      'product_id' => $product_id,
      'movie_id' => $movie_id,
      'name' => $name,
      'price' => $price,
      'type' => $type,
      'genre' => $genre,
      'description' => $description,
      'quantity_in_stock' => $quantity_in_stock,
      'image' => $image
    );

    array_push($products_arr['data'], $product_item);

  }

  echo json_encode($products_arr);
}
else{
  echo json_encode(
    array('message' => 'No Products Found')
  );
}