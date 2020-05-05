<?php

class Order {
  private $conn;
  private $table = 'orders';

  public $customer_id;
  public $total_price;
  public $order_items;
  public $order_item_string = [];
  public $product_item_string = [];
  public $product_ids = [];

  public function __construct($db) {
    $this->conn = $db;
  }

  //create a product
  public function create() {

    //user may be wanting to order a lot of products, so we loop through those here in this data point (which is an array) before sending the info to our query. The last_insert_id is referencing the order id that first gets created with the above two $_POST data points being set
    foreach($this->order_items as $order) {
      $order_item_string[] = '(LAST_INSERT_ID(),' . intval($order['product_id']) . ',' . intval($order['quantityOrdered']) . ')';
      $product_item_string[] = 'WHEN ' . intval($order['product_id']) . ' THEN ' . intval($order['quantityRemaining']) . ' ';
      $product_ids[] = intval($order['product_id']);
    }

    $order_item_string = implode(',', $order_item_string);
    $product_item_string = implode(',', $product_item_string);
    $product_item_string = preg_replace("/(?<!\d)(\,|\.)(?!\d)/", "", $product_item_string);
    $product_ids = implode(',', $product_ids);

    $query_1 =
    'INSERT INTO orders (customer_id, total_price) VALUES(:customer_id, :total_price)';

    $query_2 =
    'INSERT INTO order_details VALUES ' . $order_item_string;

    $query_3 =
      'UPDATE products SET quantity_in_stock = CASE product_id '. $product_item_string .' ELSE quantity_in_stock END WHERE product_id IN (' . $product_ids . ')';

    $stmt_1 = $this->conn->prepare($query_1);
    $stmt_2 = $this->conn->prepare($query_2);
    $stmt_3 = $this->conn->prepare($query_3);

    //clean data
    $this->customer_id = htmlspecialchars(strip_tags($this->customer_id));
    $this->total_price = htmlspecialchars(strip_tags($this->total_price));

    //bind data
    $stmt_1->bindParam(':customer_id', $this->customer_id);
    $stmt_1->bindParam(':total_price', $this->total_price);

     //execute query
    //if everything executes okay, return true
    if($stmt_1->execute() && $stmt_2->execute() && $stmt_3->execute()) {
      return true;
    }

    //print error if something goes wrong
    //%s is a placeholder, and \n is new line
    printf("Error: %s.\n", $stmt_1->error);

    return false;

  }


  public function read() {
    $query =
      'SELECT
        c.first_name,
        c.last_name,
        c.email,
        o.order_id,
        o.order_date,
        o.total_price,
        od.quantity,
        p.name,
        p.price,
        p.type,
        p.image
      FROM customers c
      JOIN orders o
        ON c.customer_id = o.customer_id
      JOIN order_details od
        ON o.order_id = od.order_id
      JOIN products p
        ON od.product_id = p.product_id
      ORDER BY o.order_date';

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt;
  }
}