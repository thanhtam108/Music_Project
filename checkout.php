<?php
session_start();
include 'DBconnection.php';

if (!isset($_SESSION['loggedCUS_in']) || !isset($_SESSION['customer_id'])) {
    echo "Bạn cần đăng nhập để thanh toán.";
    exit;
}

$customer_id = $_SESSION['customer_id'];

$dbConnection = new DBConnection();
$conn = $dbConnection->conn;

// Lấy thông tin giỏ hàng
$stmt = $conn->prepare("SELECT t_cart.dev_id, t_cart.quantity, t_device.DEV_NAME, t_device.DEV_PRICE FROM t_cart JOIN t_device ON t_cart.dev_id = t_device.DEV_ID WHERE t_cart.customer_id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_price = 0;
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['DEV_PRICE'] * $row['quantity'];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
</head>
<body>
    <header id="header">
        <!-- Nội dung header của bạn ở đây -->
    </header>

    <section id="checkout-section">
        <h1>Thanh toán</h1>
        <?php if (!empty($cart_items)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['DEV_NAME']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($item['DEV_PRICE'], 2)); ?> VND</td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($item['DEV_PRICE'] * $item['quantity'], 2)); ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h2>Tổng cộng: <?php echo htmlspecialchars(number_format($total_price, 2)); ?> VND</h2>
            <form action="process_order.php" method="POST">
                <button type="submit">Xác nhận mua hàng</button>
            </form>
        <?php else: ?>
            <p>Giỏ hàng của bạn đang trống.</p>
        <?php endif; ?>
    </section>
</body>
</html>
