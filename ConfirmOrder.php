<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending order</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
</head>
<body>
    <?php
        include "Order.php";
        $order = new Order();
        $pending_orders = $order->get_pending_order();


    ?>
</body>
</html>