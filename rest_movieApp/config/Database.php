<?php
session_start();

class Database {
  private $host = "localhost";
  private $db_name = "video_and_more";
  private $username = "tom";
  private $password = "123";
  private $conn;

  public function connect() {
    $this->conn = null;

    try{
      $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);

      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $exception){
      echo "Connection error: " . $exception->getMessage();
    }

    return $this->conn;

  }
}