<?php

class Product {
  private $conn;
  private $table = 'products';

  public $product_id;
  public $name;
  public $price;
  public $genre;
  public $product_type;
  public $description;
  public $quantity_in_stock;

  public function __construct($db) {
    $this->conn = $db;
  }

  //create a product
  public function create() {
    $query =
    'INSERT INTO products
      SET
        `name` = :nm,
        price = :price,
        genre = :genre,
        product_type = :product_type,
        `description` = :descr,
        quantity_in_stock = :quantity_in_stock';

    $stmt = $this->conn->prepare($query);

    //clean data
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->price = htmlspecialchars(strip_tags($this->price));
    $this->genre = htmlspecialchars(strip_tags($this->genre));
    $this->product_type = htmlspecialchars(strip_tags($this->product_type));
    $this->description = htmlspecialchars(strip_tags($this->description));
    $this->quantity_in_stock = htmlspecialchars(strip_tags($this->quantity_in_stock));

    //bind data
    $stmt->bindParam(':nm', $this->name);
    $stmt->bindParam(':price', $this->price);
    $stmt->bindParam(':genre', $this->genre);
    $stmt->bindParam(':product_type', $this->product_type);
    $stmt->bindParam(':descr', $this->description);
    $stmt->bindParam(':quantity_in_stock', $this->quantity_in_stock);

     //execute query
    //if everything executes okay, return true
    if($stmt->execute()) {
      return true;
    }

    //print error if something goes wrong
    //%s is a placeholder, and \n is new line
    printf("Error: %s.\n", $stmt->error);

    return false;

  }


  //read all products
  public function read() {
    $query =
      'SELECT *
      FROM products
      ORDER BY product_id';

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt;
  }

}