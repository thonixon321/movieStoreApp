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
  public $rating;
  public $search;
  public $quantity_in_stock;
  public $message;
  public $customer_id;

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
      ORDER BY `name`';

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt;
  }

  //read all product ratings
  public function readRatings() {
    $query =
      'SELECT *
      FROM product_reviews pr
      JOIN products p
        ON pr.movie_id = p.movie_id
      ORDER BY pr.movie_id';

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt;
  }


  public function read_search() {
    //find movie titles that have a match, anywhere in their title, for the user search
    $this->search = htmlspecialchars($this->search);
    $has = "%$this->search%";
    $query =
    'SELECT *
    FROM products
    WHERE `name` LIKE :has
    ORDER BY `name`';

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':has', $has);

    $stmt->execute();

    return $stmt;

  }

  public function rate() {
    $ratingNum;
    $name = htmlspecialchars($this->name);
    $rating = htmlspecialchars($this->rating);
    $customer_id = htmlspecialchars($this->customer_id);
    $message = htmlspecialchars($this->message);
    //check to see if product has been rated yet
    $query =
    'SELECT num_of_ratings
    FROM products
    WHERE movie_id = :movie_name';

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':movie_name', $name);

    try {
      $stmt->execute();
    }
    catch (PDOException $e) {
      printf("Error: %s.\n", $e->getMessage());
      throw new Exception("Database query error");
    }

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      extract($row);
      if ($num_of_ratings == 0) {
        $ratingNum = 1;
      }
      else {
        $ratingNum = 2;
      }
    }

    $query =
    'UPDATE products
      SET
        rating_avg = (rating_avg + :user_rating) / ' . $ratingNum . ',
        num_of_ratings = num_of_ratings + 1
      WHERE movie_id = :movie_name';

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_rating', $rating);
    $stmt->bindParam(':movie_name', $name);

    try {
      $stmt->execute();
    }
    catch(PDOException $e) {
      printf("Error: %s. \n", $e->getMessage());
      throw new Exception("Database query error");
    }

    $query =
    'INSERT INTO product_reviews (movie_id, customer_id, `message`, rating)
      VALUES(:movie_name, :customer_id, :msg, :user_rating)';

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':movie_name', $name);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->bindParam(':msg', $message);
    $stmt->bindParam(':user_rating', $rating);

    if ($stmt->execute()) {
      return true;
    }
    else {
      return false;
    }


  }

}