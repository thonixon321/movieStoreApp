<?php
header('Access-Control-Allow-Origin: http://localhost:8082');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
include_once '../../config/Database.php';
include_once '../../models/Customer.php';

//needed for axios
$_POST = json_decode(file_get_contents("php://input"), true);

$customer = new Customer('no db');

//make sure the fields are not empty before sending
if (!empty($_POST['email']) && isset($_POST['email']) && $customer->isEmailValid($_POST['email'])) {

?>

<html>

  <head>
    <title>HTML email using PHP</title>
  </head>
    <body>

    <?php

      $to = $_POST['email'];
      $subject = 'Reset Video and More Password';
      $message = "<b>Please click the link below to change your password</b><br>
                  <a href=http://localhost:8082/#/new-password>New Password</a>";

      mail ($to, $subject, $message);

    ?>

    </body>


</html>

<?php
  echo json_encode(
    array(
      'message' => 'emailed user successfully',
    )
  );
}
else{
  echo json_encode(
    array('message' => 'did not email user')
  );
}

?>