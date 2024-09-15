<?php
include 'DB_connection.php';
$dbConnection = new DBConnection();
$conn = $dbConnection->conn;

if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
    $sql = "SELECT * FROM T_CUSTOMER WHERE CUSTOMER_PHONE = '$phone'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "phoneExists";
    } else {
        echo "phoneNotExists";
    }
}

if (isset($_POST['account'])) {
    $account = $_POST['account'];
    $sql = "SELECT * FROM T_CUSTOMER WHERE CUSTOMER_ACCOUNT = '$account'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "accountExists";
    } else {
        echo "accountNotExists";
    }
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $sql = "SELECT * FROM T_CUSTOMER WHERE CUSTOMER_EMAIL = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "emailExists";
    } else {
        echo "emailNotExists";
    }
}
