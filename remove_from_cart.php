<?php
session_start();
include 'DBconnection.php';

if (!isset($_SESSION['loggedCUS_in']) || !isset($_SESSION['customer_id'])) {
    echo "Bạn cần đăng nhập để xóa sản phẩm khỏi giỏ hàng.";
    exit;
}

$customer_id = $_SESSION['customer_id'];
$product_id = $_POST['product_id'];

$dbConnection = new DBConnection();
$conn = $dbConnection->conn;

// Xóa sản phẩm khỏi giỏ hàng
$stmt = $conn->prepare("DELETE FROM t_cart WHERE customer_id = ? AND dev_id = ?");
$stmt->bind_param("ii", $customer_id, $product_id);

if ($stmt->execute()) {
    echo "Sản phẩm đã được xóa khỏi giỏ hàng.";
} else {
    echo "Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng.";
}

$stmt->close();
$conn->close();

// Chuyển hướng về trang giỏ hàng
header("Location: cart.php");
exit;
?>
