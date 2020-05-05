<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title>Movie App</title>
</head>
<body>
  <div>Hello movie app!</div>
  <?php
    $postItems = [
      [
        'product_id' => 1,
        'quantity' => 1
      ],
      [
        'product_id' => 2,
        'quantity' => 1
      ]
      ];
    $order_items = [];

    foreach ($postItems as $order) {
      $order_items[] = '(LAST_INSERT_ID(),' . $order['product_id'] . ',' . $order['quantity'] . ')';
    }

    // echo $order_items . '<br>';
    echo implode(',', $order_items);

  ?>
</body>
</html>