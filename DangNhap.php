<?php
session_start();

include 'DBConnection.php';
$dbConnection = new DBConnection();
$conn = $dbConnection->conn;
$isEmpLogged = false;
$isCusLogged = false;
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $acc = trim($_POST['account']);
    $passwd = trim($_POST['password']);

    if (!empty($acc) && !empty($passwd)) {
        $stmt_emp = $conn->prepare("SELECT EMP_PASSWORD FROM T_EMPLOYEE WHERE EMP_ACCOUNT = ?");
        $stmt_emp->bind_param("s", $acc);
        $stmt_emp->execute();
        $stmt_emp->store_result();
        if ($stmt_emp->num_rows > 0) {
            $stmt_emp->bind_result($db_password);
            $stmt_emp->fetch();
            if ($passwd === $db_password) {
                $isEmpLogged = true;
            } else {
                $msg = "Wrong user name or password!";
            }
        } else {
            $stmt_cus = $conn->prepare("SELECT CUSTOMER_ID, CUSTOMER_PASSWORD FROM T_CUSTOMER WHERE CUSTOMER_ACCOUNT = ?");
            $stmt_cus->bind_param("s", $acc);
            $stmt_cus->execute();
            $stmt_cus->store_result();
            if ($stmt_cus->num_rows > 0) {
                $stmt_cus->bind_result($customer_id, $db_password);
                $stmt_cus->fetch();
                if ($passwd === $db_password) {
                    $isCusLogged = true;
                } else {
                    $msg = "Wrong user name or password!";
                }
            } else {
                $msg = "User not found!";
            }
            $stmt_cus->close();
        }
        $stmt_emp->close();
    } else {
        $msg = "Please fill in both fields.";
    }
}

if ($isEmpLogged) {
    session_start();
    if ($acc === "admin1" || $acc === "admin2") {
        $_SESSION['loggedADM_in'] = $acc;
    } else {
        $_SESSION['loggedEMP_in'] = $acc;
    }
    header('Refresh: 0.5; URL = index.php');
    exit();
}

if ($isCusLogged) {
    session_start();
    $_SESSION['customer_id'] = $customer_id;
    $_SESSION['loggedCUS_in'] = $acc;
    header('Refresh: 0.5; URL = index.php');
    exit();
}
$conn->close();

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleDangnhap.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="close-button" onclick="window.location.href = 'index.php';">Close</div>
        <h2>Đăng nhập</h2>
        <h4 class="error-message"><?php echo $msg; ?></h4>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="account">Account:</label>
                <input type="text" name="account" id="account" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" name="login">Log in</button>
            <p>Bạn chưa có tài khoản? <a href="DangKy.php">Đăng ký ngay!</a></p>
        </form>
    </div>
</body>

</html>