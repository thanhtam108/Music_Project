<?php
session_start();
include 'DBconnection.php';

if (!isset($_SESSION['loggedCUS_in']) || !isset($_SESSION['customer_id'])) {
    echo "Bạn cần đăng nhập để mua hàng.";
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Lấy số điện thoại của khách hàng
$dbConnection = new DBConnection();
$conn = $dbConnection->conn;

$stmt = $conn->prepare("SELECT CUSTOMER_PHONE FROM T_CUSTOMER WHERE CUSTOMER_ID = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$customer_phone = $customer['CUSTOMER_PHONE'];

$stmt->close();

// Bắt đầu transaction
$conn->begin_transaction();

try {
    // Tạo đơn hàng tạm thời mới
    $stmt = $conn->prepare("INSERT INTO T_TEMPORARY_ORDER (CUSTOMER_PHONE, Total_amount, ORDER_NOTES) VALUES (?, 0, '')");
    $stmt->bind_param("s", $customer_phone);
    $stmt->execute();
    $temp_order_id = $stmt->insert_id;
    $stmt->close();

    // Lấy thông tin giỏ hàng
    $stmt = $conn->prepare("SELECT t_cart.dev_id, t_cart.quantity, t_device.DEV_PRICE FROM t_cart JOIN t_device ON t_cart.dev_id = t_device.DEV_ID WHERE t_cart.customer_id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_price = 0;

    // Lưu thông tin thiết bị vào đơn hàng tạm thời
    while ($row = $result->fetch_assoc()) {
        $dev_id = $row['dev_id'];
        $quantity = $row['quantity'];
        $price = $row['DEV_PRICE'];

        $stmt_insert_device = $conn->prepare("INSERT INTO T_TEMPORARY_ORDER_DEVICES (TEMP_ORDER_ID, DEV_ID) VALUES (?, ?)");
        $stmt_insert_device->bind_param("ii", $temp_order_id, $dev_id);
        $stmt_insert_device->execute();
        $stmt_insert_device->close();

        $total_price += $price * $quantity;
    }

    $stmt->close();

    // Cập nhật tổng giá trị đơn hàng tạm thời
    $stmt_update_order = $conn->prepare("UPDATE T_TEMPORARY_ORDER SET Total_amount = ? WHERE TEMP_ORDER_ID = ?");
    $stmt_update_order->bind_param("di", $total_price, $temp_order_id);
    $stmt_update_order->execute();
    $stmt_update_order->close();

    // Xóa giỏ hàng
    $stmt_delete_cart = $conn->prepare("DELETE FROM t_cart WHERE customer_id = ?");
    $stmt_delete_cart->bind_param("i", $customer_id);
    $stmt_delete_cart->execute();
    $stmt_delete_cart->close();

    // Commit transaction
    $conn->commit();

    echo "<p>Đơn hàng của bạn đã được xử lý thành công.</p>";
    echo "<a href='index.php'>Trở về trang chủ</a>";
} catch (Exception $e) {
    // Rollback transaction nếu có lỗi
    $conn->rollback();
    echo "Có lỗi xảy ra khi xử lý đơn hàng: " . $e->getMessage();
}

$conn->close();
?>
