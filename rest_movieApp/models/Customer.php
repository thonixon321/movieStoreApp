<?php

class Customer {
  private $conn;
  private $table = 'customers';

  public $customer_id;
  public $first_name;
  public $last_name;
  public $email;
  public $password;

  public function __construct($db) {
    $this->conn = $db;
  }

  //read all customers
  public function read() {
    $query =
      'SELECT *
      FROM customers
      ORDER BY customer_id';

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt;
  }

  //create a customer
  public function create() {
    $query =
    'INSERT INTO customers (first_name, last_name, email, `password`)
      VALUES (:first_name, :last_name, :email, :pwd)';

    $stmt = $this->conn->prepare($query);

    //clean data
    $this->first_name = htmlspecialchars($this->first_name);
    $this->last_name = htmlspecialchars($this->last_name);
    $this->email = htmlspecialchars($this->email);
    $this->password = htmlspecialchars($this->password);

    //bind data
    $stmt->bindParam(':first_name', $this->first_name);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':pwd', $this->password);
    $stmt->bindParam(':last_name', $this->last_name);

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
}