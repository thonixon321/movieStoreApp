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

$rateResult = $product->readRatings();

$rateNum = $rateResult->rowCount();

if ($rateNum > 0) {
  $ratings_arr = array();

  while ($ratingRow = $rateResult->fetch(PDO::FETCH_ASSOC)) {
    extract($ratingRow);

    $rating_item = array(
      'movie_id' => $movie_id,
      'customer_id' => $customer_id,
      'message' => $message,
      'rating' => $rating,
      'rating_avg' => $rating_avg,
      'num_of_ratings' => $num_of_ratings
    );

    array_push($ratings_arr, $rating_item);
  }
  echo json_encode($ratings_arr);
}
else {
  echo json_encode(
    array('message' => 'No Product Ratings Found')
  );
}