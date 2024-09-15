<?php
session_start();
if (!isset($_SESSION['loggedEMP_in'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
</head>
<body>
    <header id="header">
        <div id="header-container">
            <div id="logo">
                <a href="index.php"><b>MusicStore</b></a>
            </div>
            <nav id="main-nav">
                <ul id="nav-list">
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="About.html">Về chúng tôi</a></li>
                    <li><a href="Contact.html">Liên hệ</a></li>
                    <li><a href="admin_dashboard.php">Quản lý Cửa hàng</a></li>
                </ul>
                <a id="cart-logo" href="cart.php"><i class="fa fa-shopping-cart" style="font-size:24px"></i></a>
                <div class="dropdown">
                    <a href="#"><img id="user-logo" src="images/user-icon.png" alt=""></a>
                    <div class="dropdown-content">
                        <a href="ThongTinAccount.php">Tài khoản</a>
                        <a href="DangXuat.php">Đăng xuất</a>
                    </div>
                </div>
            </nav>
        </div>
        <br>

        <form class="search-bar" action="search_result.php" method="GET" onsubmit="return handleSearch()">
            <input type="text" placeholder="Tìm kiếm.." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </header>

    <section id="emp-functions">
        <h1>Chức năng quản lý Cửa hàng</h1>
        <ul>
            <li><a href="AddDevice.php">Thêm sản phẩm</a></li>
            <li><a href="RemoveDevice.php">Xoá sản phẩm</a></li>
            <li><a href="UpdateDeviceList.php">Sửa thông tin sản phẩm</a></li>
            <li><a href="TempOrderList.php">Quản lý đơn hàng</a></li>
        </ul>
    </section>
</body>
</html>
