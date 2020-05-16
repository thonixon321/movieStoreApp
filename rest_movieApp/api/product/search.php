<?php
//THIS ENDPOINT READS ALL THE MOVIES IN PRODUCTS TABLE THAT CONTAIN THE GIVEN SEARCH NAME
//Headers (required for http request)
header('Access-Control-Allow-Origin: http://localhost:8082');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
include_once '../../config/Database.php';
include_once '../../models/Product.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

//Instantiate product object
$product = new Product($db);

//Get Search (use the super global GET and check if it is set 'isset')
//e.g. something.com?id=3 - we can access the 3 here -
//it comes from the url, not the product model -
//and if it doesn't exist, then we kill the request
$product->search = isset($_GET['search']) ? $_GET['search'] : die();

//Get product from user search
$result = $product->read_search();

$num = $result->rowCount();

if ($num > 0) {
  $products_arr = array();
  $products_arr['data'] = array();

  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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

  //Make JSON
  echo json_encode($products_arr);
}
else{
  echo json_encode(
    array('message' => 'No Products Found')
  );
}

