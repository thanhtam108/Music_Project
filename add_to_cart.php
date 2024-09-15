<?php
session_start();
include 'DBconnection.php';

$response = array();

if (!isset($_SESSION['loggedCUS_in']) || !isset($_SESSION['customer_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$customer_id = $_SESSION['customer_id'];
$product_id = $_POST['product_id'];
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

$dbConnection = new DBConnection();
$conn = $dbConnection->conn;

$stmt = $conn->prepare("SELECT * FROM t_cart WHERE customer_id = ? AND dev_id = ?");
$stmt->bind_param("ii", $customer_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE t_cart SET quantity = quantity + ? WHERE customer_id = ? AND dev_id = ?");
    $stmt->bind_param("iii", $quantity, $customer_id, $product_id);
} else {
    $stmt = $conn->prepare("INSERT INTO t_cart (customer_id, dev_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $customer_id, $product_id, $quantity);
}

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Sản phẩm đã được thêm vào giỏ hàng.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.';
}

$stmt->close();
$conn->close();

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
