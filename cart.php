<?php
session_start();
include 'DBconnection.php';

if (!isset($_SESSION['loggedCUS_in'])) {
    echo "Bạn cần đăng nhập để xem giỏ hàng.";
    exit;
}

$user_id = $_SESSION['customer_id'];

$dbConnection = new DBConnection();
$conn = $dbConnection->conn;

$query = "SELECT t_cart.id, t_cart.quantity, T_DEVICE.DEV_NAME, T_DEVICE.DEV_PRICE, 
            T_DEVICE.DEV_IMAGEURL, T_DEVICE.BRAND_ID, T_DEVICE.DEV_ID
            FROM t_cart
            JOIN T_DEVICE ON t_cart.dev_id = T_DEVICE.DEV_ID
            WHERE t_cart.CUSTOMER_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Giỏ hàng của bạn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        .cart-items {
            list-style: none;
            padding: 0;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
        }

        .cart-item img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }

        .cart-item span {
            margin: 0 10px;
        }

        .product-addtoCart {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .product-addtoCart:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <a href="index.php">Trở về</a>
    <h1>Giỏ hàng của bạn</h1>
    <?php if (!empty($cart_items)) : ?>
        <ul class="cart-items">
            <?php 
                foreach ($cart_items as $item) :
                $brand_name = strtoupper($item['BRAND_ID']);
                switch ($brand_name) {
                    case 1:
                        $img_directory = "SONY/";
                        break;
                    case 2:
                        $img_directory = "JBL/";
                        break;
                    case 3:
                        $img_directory = "MARSHALL/";
                        break;
                    case 4:
                        $img_directory = "BOSE/";
                        break;
                    case 5:
                        $img_directory = "YAMAHA/";
                        break;
                }
            ?>
                <li class="cart-item">
                    <img src="images/<?php echo $img_directory . htmlspecialchars($item['DEV_IMAGEURL']); ?>" alt="<?php echo htmlspecialchars($item['DEV_NAME']); ?>">
                    <span><?php echo htmlspecialchars($item['DEV_NAME']); ?></span>
                    <span><?php echo htmlspecialchars($item['DEV_PRICE']); ?> VND</span>
                    <span>Số lượng: <?php echo htmlspecialchars($item['quantity']); ?></span>
                    <form action="remove_from_cart.php" method="POST" class="remove-item-form">
                        <input type="hidden" name="product_id" value="<?php echo $item['DEV_ID']; ?>">
                        <button type="submit">Xóa</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>Giỏ hàng của bạn đang trống.</p>
    <?php endif; ?>
    <a href="index.php">Tiếp tục mua sắm</a>
    <br>
    <a href="checkout.php">Tiến hành đặt hàng</a>
</body>

</html>