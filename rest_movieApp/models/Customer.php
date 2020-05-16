<?php

class Customer {
  private $conn;
  private $table = 'customers';

  public $customer_id;
  public $first_name;
  public $last_name;
  public $email;
  public $password;
  public $enabled;
  private $authenticated;

  //establish connection (passed to this model from the api endpoint)
  public function __construct($db) {
    $this->conn = $db;
  }

  /*

  .d8888b.  8888888b.  888     888 8888888b.
 d88P  Y88b 888   Y88b 888     888 888  "Y88b
 888    888 888    888 888     888 888    888
 888        888   d88P 888     888 888    888
 888        8888888P"  888     888 888    888
 888    888 888 T88b   888     888 888    888
 Y88b  d88P 888  T88b  Y88b. .d88P 888  .d88P
  "Y8888P"  888   T88b  "Y88888P"  8888888P"

*/
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
    //make sure email is valid
    if (!$this->isEmailValid($this->email)) {
      throw new Exception('invalid email');
    }

    //make sure password is valid
    if (!$this->isPasswordValid($this->password)) {
      throw new Exception('invalid password');
    }

    //check if email already exists in DB (method will return null if email doesn't already exist)
    if (!is_null($this->getIdFromEmail($this->email))) {
      throw new Exception('email already exists');
    }

    //clean data
    $this->first_name = htmlspecialchars($this->first_name);
    $this->last_name = htmlspecialchars($this->last_name);
    $this->email = htmlspecialchars($this->email);
    $this->password = htmlspecialchars($this->password);

    $hash = password_hash($this->password, PASSWORD_DEFAULT);

    //bind data
    $stmt->bindParam(':first_name', $this->first_name);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':pwd', $hash);
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

  //update a customer's password
  public function update() {
    $this->password = trim($this->password);

    //make sure the id is valid
    if (!$this->isIdValid($this->customer_id)) {
      throw new Exception('invalid customer id');
    }

    //make sure password is valid
    if (!$this->isPasswordValid($this->password)) {
      throw new Exception('invalid password');
    }

    $query = 'UPDATE customers
              SET
                  `password` = :pword
              WHERE customer_id = :id';
    //don't show password in DB
    $hash = password_hash($this->password, PASSWORD_DEFAULT);

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':pword', $hash);
    $stmt->bindParam(':id', $this->customer_id);
    //another way to execute query would be in a try/catch, this contrasts the
    //create method above as an alternate example
    try {
     $stmt->execute();
    }
    catch (PDOException $e) {
      printf("Error: %s.\n", $e->getMessage());
      throw new Exception("Database query error");
    }

    return true;

  }


  //delete customer - and any associated orders/order_details
  public function delete() {
    //check if customer id is valid
    if (!$this->isIdValid($this->customer_id)) {
      throw new Exception('Invalid customer id');
    }

    $query = 'DELETE FROM customers
              WHERE customer_id = :id';

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':id', $this->customer_id);

    try {
      $stmt->execute();
    }
    catch (PDOException $e) {
      printf("Error: %s.\n", $e->getMessage());
      throw new Exception('Database query error');
    }
    //delete sessions related to the account
    $query = 'DELETE FROM customers_sessions
              WHERE (customer_id = :id)';

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->customer_id);

    try {
      $stmt->execute();
    }
    catch (PDOException $e) {
      throw new Exception('Database query error');
    }

    //delete rows from orders and order details related to the customer account that was deleted
    $query = 'DELETE o, od
              FROM orders o
              JOIN order_details od
                ON o.order_id = od.order_id
              WHERE (o.customer_id = :id)';

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->customer_id);

    try {
      $stmt->execute();
    }
    catch (PDOException $e) {
      throw new Exception('Database query error');
    }
    //if both query executions pass, then return true to api call
    return true;
  }

  /*

 888                   d8b
 888                   Y8P
 888
 888  .d88b.   .d88b.  888 88888b.
 888 d88""88b d88P"88b 888 888 "88b
 888 888  888 888  888 888 888  888
 888 Y88..88P Y88b 888 888 888  888
 888  "Y88P"   "Y88888 888 888  888
                   888
              Y8b d88P
               "Y88P"

*/

//use the email and password to log user in,
//returns true or false depending on if the email and password
//are correct
public function login() {

  $this->email = trim($this->email);
  $this->password = trim($this->password);

  //make sure email is valid
  if (!$this->isEmailValid($this->email)) {
    return false;
  }

  //make sure password is valid
  if (!$this->isPasswordValid($this->password)) {
    return false;
  }

  //query to see if email exists and is enabled
  $query = 'SELECT *
            FROM customers
            WHERE (email = :email) AND (account_enabled = 1)';

  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':email', $this->email);

  try {
    $stmt->execute();
  }
  catch (PDOException $e) {
    throw new Exception('Database query error');
  }
  //store the response as array
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  //check if the response is successful (email was found and is in array now)
  if (is_array($row)) {
    //need to check if the password given matches the one that
    //corresponds to the query we just made - the password_verify will match the hashing algorithm stored in DB
    if (password_verify($this->password, $row['password'])) {
      /* Authentication succeeded. Set the class properties (id and email) */
			$this->customer_id = intval($row['customer_id'], 10);
			$this->email = $row['email'];
			$this->authenticated = true;

			/* Register the current Sessions on the database */
			$this->registerLoginSession();

			/* Finally, Return true */
			return true;
    }
  }


}


/*

 888                                     888
 888                                     888
 888                                     888
 888  .d88b.   .d88b.   .d88b.  888  888 888888
 888 d88""88b d88P"88b d88""88b 888  888 888
 888 888  888 888  888 888  888 888  888 888
 888 Y88..88P Y88b 888 Y88..88P Y88b 888 Y88b.
 888  "Y88P"   "Y88888  "Y88P"   "Y88888  "Y888
                   888
              Y8b d88P
               "Y88P"

*/
// The logout() method clears the account-related class properties (customer_id and email) and deletes the current Session from the customers_sessions table.

// The PHP Session itself is not closed because there is no need for it. Also, the Session may be needed by other sections of the web application.

// However, this Session can no longer be used to log in with sessionLogin()
//Log out current user
public function logout() {
  //if no user, do nothing
  if (is_null($this->customer_id)) {
    return;
  }

  //reset account-related props
  $this->customer_id = null;
  $this->email = null;
  $this->authenticated = false;

  //if open session, remove it from the customers_sessions table
  if (session_status() == PHP_SESSION_ACTIVE) {
    $query = 'DELETE FROM customers_sessions
              WHERE (session_id = :sessid)';

    $stmt = $this->conn->prepare($query);
    $sessId = session_id();
    $stmt->bindParam(':sessid', $sessId);

    try {
      $stmt->execute();
      return true;
    }
    catch (PDOException $e) {
      throw new Exception('Database query error');
    }
  }

  return false;
}

/*

  .d8888b.           888    888
 d88P  Y88b          888    888
 888    888          888    888
 888         .d88b.  888888 888888 .d88b.  888d888 .d8888b
 888  88888 d8P  Y8b 888    888   d8P  Y8b 888P"   88K
 888    888 88888888 888    888   88888888 888     "Y8888b.
 Y88b  d88P Y8b.     Y88b.  Y88b. Y8b.     888          X88
  "Y8888P88  "Y8888   "Y888  "Y888 "Y8888  888      88888P'




*/

/* "Getter" function for the $authenticated variable
    Returns TRUE if the remote user is authenticated */
public function isAuthenticated(): bool
{
  return $this->authenticated;
}


public function getName() {
  return $this->first_name . " " . $this->last_name;
}

public function getId() {
  return $this->customer_id;
}

public function getEmail() {
  return $this->email;
}


/*

                                     d8b
                                     Y8P

 .d8888b   .d88b.  .d8888b  .d8888b  888  .d88b.  88888b.
 88K      d8P  Y8b 88K      88K      888 d88""88b 888 "88b
 "Y8888b. 88888888 "Y8888b. "Y8888b. 888 888  888 888  888
      X88 Y8b.          X88      X88 888 Y88..88P 888  888
  88888P'  "Y8888   88888P'  88888P' 888  "Y88P"  888  888




*/

//inserts a new row into the customers_sessions table, with the current Session ID (given by the session_id() function) in the session_id column, the authenticated account ID in the account_id column and the login_time column set to the current timestamp.
private function registerLoginSession() {
  //check that session has been started
  if (session_status() == PHP_SESSION_ACTIVE) {
    	/* 	Use a REPLACE statement to:
        - insert a new row with the session id, if it doesn't exist, or...
        - update the row having the session id, if it does exist.
      */
      $query = 'REPLACE INTO customers_sessions
                (session_id, customer_id, login_time)
                VALUES (:sessid, :customerId, NOW())';

      $stmt = $this->conn->prepare($query);
      $sessId = session_id();
      $stmt->bindParam(':sessid', $sessId);
      $stmt->bindParam(':customerId', $this->customer_id);

      try {
        $stmt->execute();
      }
      catch (PDOException $e) {
        throw new Exception('Database query error');
      }

  }
}


//This next method gets the current Session ID (using session_id()) and looks for it into the account_sessions table.
// If it’s there, it also checks that:
// the Session is not older than 7 days
// the account linked to the session is enabled
// If everything is ok then the client is authenticated, the account-related class properties are set, and the method returns TRUE.
public function sessionLogin() {
  //check that the session has been started
  if (session_status() == 2) {
    /*
			Query template to look for the current session ID on the account_sessions table.
			The query also makes sure the Session is not older than 7 days
    */
    $query = 'SELECT *
              FROM customers_sessions, customers
              WHERE
              (customers_sessions.session_id = :sessid) AND
              (customers_sessions.login_time >= (NOW() - INTERVAL 7 DAY)) AND
              (customers_sessions.customer_id = customers.customer_id) AND
              (customers.account_enabled = 1)';

    $stmt = $this->conn->prepare($query);
    $sessId = session_id();

    $stmt->bindParam(':sessid', $sessId);

    try {
      $stmt->execute();
    }
    catch (PDOException $e) {
      throw new Exception('Database query error');
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (is_array($row)) {
      //session login succeeded since the query returned a result
      //so set the login properties and return true
      $this->customer_id = intval($row['customer_id'], 10);
      $this->email = $row['email'];
      $this->first_name = $row['first_name'];
      $this->last_name = $row['last_name'];
      $this->authenticated = true;

      return true;
    }
  }

  //auth failed
  return false;
}


/* Close all account Sessions except for the current one (aka: "logout from other devices") */
public function closeOtherSessions() {
  //if no logged in user, do nothing
  if (is_null($this->customer_id)) {
    return;
  }

  //check that a session has been started
  if (session_status() == PHP_SESSION_ACTIVE) {
    //delete all account Sessions with session_id different from the current one
    $query = 'DELETE FROM customers_sessions
              WHERE (session_id != :sessid) AND (customer_id = :customerId';

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':sessid', session_id());
    $stmt->bindParam(':customerId', $this->customer_id);

    try {
      $stmt->execute();
    }
    catch (PDOException $e) {
      throw new Exception('Database query error');
    }

  }
}


  /*

                           d8b  .d888
                           Y8P d88P"
                               888
 888  888  .d88b.  888d888 888 888888 888  888
 888  888 d8P  Y8b 888P"   888 888    888  888
 Y88  88P 88888888 888     888 888    888  888
  Y8bd8P  Y8b.     888     888 888    Y88b 888
   Y88P    "Y8888  888     888 888     "Y88888
                                           888
                                      Y8b d88P
                                       "Y88P"

*/

  /* A sanitization check for the customer ID */
  public function isIdValid(int $id): bool
  {
    /* Initialize the return variable */
    $valid = true;

    /* Example check: the ID must be between 1 and 1000000 */

    if (($id < 1) || ($id > 1000000))
    {
      $valid = false;
    }

    /* You can add more checks here */

    return $valid;
  }

  //check email
  public function isEmailValid(string $email): bool {
    //initialize return var
    $valid = true;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $valid = false;
    }

    return $valid;

  }

  //check password
  public function isPasswordValid(string $password): bool {
    //initialize return var
    $valid = true;

    $len = mb_strlen($password);
    //password should be between 8 and 16 chars
    if (($len < 8) || ($len > 16)) {
      $valid = false;
    }

    return $valid;

  }

  //check if email already exists in DB
  public function getIdFromEmail(string $email): ?int {
    //make sure email is valid - checked again since method is public
    if (!$this->isEmailValid($email)) {
      throw new Exception('invalid email');
    }

    $id = null;

    //search id on DB where email matches
    $query = 'SELECT customer_id
              FROM customers
              WHERE (email = :email)';

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':email', $email);

    try {
      $stmt->execute();
    }
    catch (PDOException $e) {
      printf("Error: %s.\n", $e->getMessage());
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //id will be in the $row if email already exists in DB
    if (is_array($row)) {
      $id = intval($row['customer_id'], 10);
    }

    return $id;

  }



}
