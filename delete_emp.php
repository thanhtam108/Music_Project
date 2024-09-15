<?php
session_start();
include 'DBconnection.php';

// Kiểm tra quyền truy cập của quản trị hệ thống
if (!isset($_SESSION['loggedADM_in'])) {
    echo "Bạn cần đăng nhập với quyền quản trị để xóa nhân viên.";
    exit;
}

$emp_id = $_POST['emp_id'];

$dbConnection = new DBConnection();
$conn = $dbConnection->conn;

// Xóa nhân viên khỏi cơ sở dữ liệu
$stmt = $conn->prepare("DELETE FROM T_EMPLOYEE WHERE EMP_ID = ? AND EMP_ACCOUNT != 'admin'");
$stmt->bind_param("s", $emp_id);

if ($stmt->execute()) {
    echo "Nhân viên đã được xóa thành công.";
} else {
    echo "Có lỗi xảy ra khi xóa nhân viên.";
}

$stmt->close();
$conn->close();

// Chuyển hướng về trang danh sách nhân viên
header("Location: employee_list.php");
exit;
?>
