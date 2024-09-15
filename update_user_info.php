<?php
session_start();
include 'DBconnection.php';

if (!isset($_SESSION['loggedCUS_in'])) {
    echo "User not logged in.";
    exit;
}

$acc = $_SESSION['loggedCUS_in'];
$field = $_POST['field'];
$value = $_POST['value'];

$allowed_fields = ['name', 'email', 'address', 'username', 'phone'];
if (!in_array($field, $allowed_fields)) {
    echo "Invalid field.";
    exit;
}

$column_mapping = [
    'name' => 'CUSTOMER_NAME',
    'email' => 'CUSTOMER_EMAIL',
    'address' => 'CUSTOMER_ADDRESS',
    'username' => 'CUSTOMER_ACCOUNT',
    'phone' => 'CUSTOMER_PHONE'
];

$column = $column_mapping[$field];

$dbConnection = new DBConnection();
$conn = $dbConnection->conn;

$stmt = $conn->prepare("UPDATE T_CUSTOMER SET $column = ? WHERE CUSTOMER_ACCOUNT = ?");
$stmt->bind_param("ss", $value, $acc);
if ($stmt->execute()) {
    echo "Update successful.";
} else {
    echo "Update failed.";
}

$stmt->close();
$conn->close();
?>
