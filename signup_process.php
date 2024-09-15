<?php
include 'DBConnection.php';
$dbConnection = new DBConnection();
$conn = $dbConnection->conn;
$loginCUS_ok = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $acc = $_POST['account'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO `T_CUSTOMER` (CUSTOMER_PHONE, CUSTOMER_NAME, CUSTOMER_EMAIL, CUSTOMER_ADDRESS, CUSTOMER_ACCOUNT, CUSTOMER_PASSWORD)
            VALUES ('$phone', '$name', '$email', '$address', '$acc', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Truy vấn để lấy CUSTOMER_ID
        $sql_id = "SELECT CUSTOMER_ID FROM T_CUSTOMER WHERE 
                   CUSTOMER_PHONE = '$phone' AND CUSTOMER_NAME = '$name'";
        $result = $conn->query($sql_id);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $customer_id = $row['CUSTOMER_ID'];

            // Lưu thông tin vào session
            session_start();
            $_SESSION['loggedCUS_in'] = $acc;
            $_SESSION['customer_id'] = $customer_id;

            // Chuyển hướng tới trang chủ sau khi đăng ký thành công
            header('Refresh: 0.5; URL = index.php');
            exit();
        } else {
            echo "Không thể lấy CUSTOMER_ID.";
        }
    } else {
        echo "Đã xảy ra lỗi khi đăng ký: " . $conn->error;
    }

    $conn->close();
}
?>
