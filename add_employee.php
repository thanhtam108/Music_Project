<?php
include 'DBconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = $_POST['emp_id'];
    $emp_name = $_POST['emp_name'];
    $emp_phone = $_POST['emp_phone'];
    $emp_email = $_POST['emp_email'];
    $emp_account = $_POST['emp_account'];
    $emp_password = $_POST['emp_password'];

    // Mã hoá mật khẩu
    $hashed_password = password_hash($emp_password, PASSWORD_DEFAULT);

    $dbConnection = new DBConnection();
    $conn = $dbConnection->conn;

    // Kiểm tra xem ID nhân viên, số điện thoại, email và tài khoản có duy nhất không
    $check_sql = "SELECT * FROM T_EMPLOYEE WHERE EMP_ID = ? OR EMP_PHONE = ? OR EMP_EMAIL = ? OR EMP_ACCOUNT = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ssss", $emp_id, $emp_phone, $emp_email, $emp_account);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "ID nhân viên, số điện thoại, email hoặc tài khoản đã tồn tại.";
    } else {
        // Thêm nhân viên mới
        $sql = "INSERT INTO T_EMPLOYEE (EMP_ID, EMP_NAME, EMP_PHONE, EMP_EMAIL, EMP_ACCOUNT, EMP_PASSWORD) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $emp_id, $emp_name, $emp_phone, $emp_email, $emp_account, $hashed_password);

        if ($stmt->execute()) {
            echo "Nhân viên mới đã được thêm thành công.";
        } else {
            echo "Có lỗi xảy ra. Vui lòng thử lại.";
        }

        $stmt->close();
    }

    $stmt_check->close();
    $conn->close();
}
?>
