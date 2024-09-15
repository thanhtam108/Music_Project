<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Remove Device Handle</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
</head>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("Order.php");
    $order = new Order();

    $order_id = $_POST["Order_ID"];
    echo ($order_id);
    $order->confirm_order($order_id);
}
?>

<body>
    <p> <a href="TempOrderList.php"> Back </a> </p>
</body>

</html>