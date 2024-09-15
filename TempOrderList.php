<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Order</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .order-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .device-list {
            list-style-type: none;
            padding: 0;
        }

        .device-item {
            background: #f1f1f1;
            margin: 5px 0;
            padding: 10px;
            border-radius: 4px;
        }

        .device-item:not(:last-child) {
            margin-bottom: 10px;
        }

        .btn-confirm {
            margin-top: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-confirm:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <a href="employee_dashboard.php">Trở về</a>
    <div class="container">
        <div class="order-container">
            <div class="order-header">Đơn hàng chờ duyệt</div>
            <?php
            include "Order.php";
            $order = new Order();

            $pending_orders = $order->get_pending_order();

            foreach ($pending_orders as $pending_order) {
                echo "<div class='order-details'>";
                echo "<div><strong>Mã Đơn hàng:</strong> " . $pending_order['TEMP_ORDER_ID'] . "</div>";
                echo "<div><strong>Ngày tạo:</strong> " . $pending_order['Order_DateTime'] . "</div>";
                echo "<div><strong>Tổng tiền:</strong> " . $pending_order['Total_amount'] . "</div>";
                echo "<div><strong>Ghi chú:</strong> " . $pending_order['ORDER_NOTES'] . "</div>";
                echo "<div><strong>Số điện thoại khách hàng:</strong> " . $pending_order['CUSTOMER_PHONE'] . "</div>";

                $customer_info = $order->get_customer_info($pending_order['CUSTOMER_PHONE']);
                echo "<div><strong>Tên Khách hàng:</strong> " . $customer_info['CUSTOMER_NAME'] . "</div>";
                echo "<div><strong>Email:</strong> " . $customer_info['CUSTOMER_EMAIL'] . "</div>";
                echo "<div><strong>Địa chỉ:</strong> " . $customer_info['CUSTOMER_ADDRESS'] . "</div>";

                echo "<ul class='device-list'>";
                $pending_devices = $order->get_pending_devices($pending_order['TEMP_ORDER_ID']);

                foreach ($pending_devices as $pending_device) {
                    echo "<li class='device-item'><strong>Device Name:</strong> " . $pending_device['DEV_NAME'] . "</li>";
                }

                echo "</ul>";
                echo "<form action='ConfirmHandle.php' method='post'>";
                echo "<input type='hidden' name='Order_ID' value='" . $pending_order['TEMP_ORDER_ID'] . "' />";
                echo "<button type='submit' name='updateDev' class='btn-confirm'>Duyệt đơn hàng</button>";
                echo "</form>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>

</html>