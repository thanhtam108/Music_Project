<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MusicStore</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="function.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.add-to-cart-form').on('submit', function(e) {
                e.preventDefault();

                var $form = $(this);
                var $message = $('#cart-message');

                $.ajax({
                    url: 'add_to_cart.php',
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $message.css('color', 'green').text(response.message).fadeIn().delay(3000).fadeOut();
                        } else {
                            $message.css('color', 'red').text(response.message).fadeIn().delay(3000).fadeOut();
                        }
                    },
                    error: function() {
                        $message.css('color', 'red').text('Có lỗi xảy ra. Vui lòng thử lại.').fadeIn().delay(3000).fadeOut();
                    }
                });
            });
        });
    </script>

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
                    <?php if (isset($_SESSION['loggedADM_in'])) { ?>
                        <li><a href="admin_dashboard.php">Quản lý Admin</a></li>
                    <?php } ?>
                    <?php if (isset($_SESSION['loggedEMP_in'])) { ?>
                        <li><a href="employee_dashboard.php">Quản lý Cửa hàng</a></li>
                    <?php } ?>
                </ul>
                <a id="cart-logo" href="cart.php"><i class="fa fa-shopping-cart" style="font-size:24px"></i></a>
                <div class="dropdown">
                    <a href="#"><img id="user-logo" src="images/user-icon.png" alt=""></a>
                    <?php if (
                        !isset($_SESSION['loggedEMP_in']) && !isset($_SESSION['loggedCUS_in'])
                        && !isset($_SESSION['loggedADM_in'])
                    ) { ?>
                        <div class="dropdown-content">
                            <a href="DangNhap.php">Đăng nhập</a>
                            <a href="DangKy.php">Đăng ký</a>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['loggedCUS_in'])) { ?>
                        <div class="dropdown-content">
                            <a href="ThongTinAccount.php">Tài khoản</a>
                            <a href="DangXuat.php">Đăng xuất</a>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['loggedEMP_in']) || isset($_SESSION['loggedADM_in'])) { ?>
                        <div class="dropdown-content">
                            <a href="DangXuat.php">Đăng xuất</a>
                        </div>
                    <?php } ?>
                </div>
            </nav>
        </div>
        <br>

        <form class="search-bar" action="search_result.php" method="GET" onsubmit="return handleSearch()">
            <input type="text" placeholder="Tìm kiếm.." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </header>

    <section id="section">
        <div class="headline-content">
            <img src="https://cdn.britannica.com/55/58555-050-BCCFB1B6/cassette-tape-player-Sony-Walkman-1979.jpg" alt="Sample" class="headline-image">
            <div class="headline-text">
                <h1>Cửa Hàng Âm Thanh Nhóm 4</h1>
                <p>Tìm kiếm thiết bị âm thanh chính hãng, giá tốt? Đến ngay Nhóm</p>
                <p>Đa dạng thương hiệu, Bose, JBL, Sony,... Âm thanh đỉnh cao, bùng nổ mọi cung bậc cảm xúc. Mua hàng
                    ngay!</p>
                <?php
                if (!isset($_SESSION['loggedADM_in']) && !isset($_SESSION['loggedEMP_in']) && !isset($_SESSION['loggedCUS_in'])) {
                    echo "<a href='DangKy.php' class='btn'>Đăng Ký</a>";
                }
                ?>
                <!-- <a href="DangKy.php" class="btn">Đăng Ký</a> -->
            </div>
        </div>
    </section>

    <?php
    include "Device.php";
    $device = new Device();

    $device_list = $device->get_device_list();
    $brand_list = $device->get_brand_list();
    $cate_list = $device->get_cate_list();
    ?>

    <table>
        <tr>
            <td>
                <h2>Sản phẩm bán chạy</h2>
            </td>
            <td></td>
        </tr>
    </table>
    <br>

    <div class="best-sellers">
        <ul class="product-list">

            <?php
            $j = [1, 13, 23];

            if (!empty($device_list) && !empty($brand_list) && !empty($cate_list)) {
                for ($i = 0; $i < count($j); $i++) {
                    // Kiểm tra xem ID sản phẩm có tồn tại trong danh sách thiết bị
                    if (isset($device_list[$j[$i]])) {
                        $a_device = $device_list[$j[$i]];

                        $dev_id = $a_device['DEV_ID'];
                        $dev_name = $a_device['DEV_NAME'];
                        $cate_id = $a_device['CATE_ID'];
                        $brand_id = $a_device['BRAND_ID'];
                        $dev_image = $a_device['DEV_IMAGEURL'];
                        $dev_price = $a_device['DEV_PRICE'];
                        $img_directory = "images/";

                        // Lấy tên thương hiệu từ danh sách thương hiệu
                        $brand_name = "";
                        foreach ($brand_list as $brand) {
                            if ($brand['BRAND_ID'] == $brand_id) {
                                $brand_name = $brand['BRAND_NAME'];
                                break;
                            }
                        }

                        // Lấy tên danh mục từ danh sách danh mục
                        $cate_name = "";
                        foreach ($cate_list as $category) {
                            if ($category['CATE_ID'] == $cate_id) {
                                $cate_name = $category['CATE_NAME'];
                                break;
                            }
                        }

                        $brand_name = strtoupper($brand_name);
                        switch ($brand_name) {
                            case "SONY":
                                $img_directory .= "SONY/";
                                break;
                            case "JBL":
                                $img_directory .= "JBL/";
                                break;
                            case "MARSHALL":
                                $img_directory .= "MARSHALL/";
                                break;
                            case "BOSE":
                                $img_directory .= "BOSE/";
                                break;
                            case "YAMAHA":
                                $img_directory .= "YAMAHA/";
                                break;
                        }

                        $img_directory = $img_directory . $dev_image;

                        // Hiển thị một mục món hàng trong danh sách
                        echo "<li class='product-item'>";
                        echo "<a class='product-link' href='product_detail.php?DEV_ID=" . urlencode($dev_id) . "'>";
                        echo "<img class='product-img' src='" . htmlspecialchars($img_directory) . "' alt='" . htmlspecialchars($dev_name) . "'>";
                        echo "</a>";
                        echo "<h3><a class='product-name' href='product_detail.php?DEV_ID=" . urlencode($dev_id) . "'>" . htmlspecialchars($dev_name) . "</a></h3>";
                        echo "<p class='product-cate hidden' data-cate='" . htmlspecialchars($cate_name) . "'>Category: " . htmlspecialchars($cate_name) . "</p>";
                        echo "<p class='product-brand' data-brand='" . htmlspecialchars($brand_name) . "'>Hãng: " . htmlspecialchars($brand_name) . "</p>";
                        echo "<p class='product-price' data-price='" . htmlspecialchars($dev_price) . "'>Giá: " . htmlspecialchars($dev_price) . " VND</p>";


                        // Form for "Thêm vào giỏ hàng"
                        echo "<form action='add_to_cart.php' method='POST' class='buy-now-form'>";
                        echo "<input type='hidden' name='product_id' value='" . urlencode($dev_id) . "'>";
                        echo "<input type='hidden' name='quantity' value='1'>";
                        echo "<button type='submit' class='product-buy-now'>";
                        echo "<span>Thêm vào giỏ hàng</span>";
                        echo "</button>";
                        echo "</form>";

                        echo "</li>";
                    } else {
                        // Xử lý khi ID sản phẩm không tồn tại trong danh sách thiết bị
                        echo "<li class='product-item'>";
                        echo "<p>Sản phẩm với ID " . htmlspecialchars($j[$i]) . " không tồn tại.</p>";
                        echo "</li>";
                    }
                }
            } else {
                echo "<p>Danh sách sản phẩm, thương hiệu hoặc danh mục không có dữ liệu.</p>";
            }
            ?>

        </ul>
    </div>

    <table>
        <tr>
            <td></td>
            <td>
                <h1>Tất cả sản phẩm</h1>
            </td>
        </tr>
    </table>

    <div id='cart-message' style='display:none;'></div>
    <div class="product-section">

        <div class="filter-container">
            <h2>Filters</h2>
            <!-- Brands -->
            <h3>Brands</h3>
            <div id="btnBrands">
                <button class="btn active" onclick="filterBrands('all')">ALL</button>
                <?php foreach ($brand_list as $brand) {
                    $name_brand = $brand['BRAND_NAME'];
                    echo "<button class='btn' onclick=\"filterBrands('$name_brand')\">$name_brand</button>";
                } ?>
            </div>

            <!-- Category -->
            <h3>Category</h3>
            <div>
                <select name="cate_id" id="cate-filter" title="Select category">
                    <?php
                    $categories = $device->get_cate_list();
                    if ($categories === false) {
                        echo "<option value=''>Error retrieving categories</option>";
                    } else {
                        echo "<option value='all'>All</option>";
                        foreach ($categories as $category) {
                            echo "<option>" . $category['CATE_NAME'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Price -->
            <h3>Price</h3>
            <div>
                <label for="min-price">Min Price:</label>
                <input type="number" id="min-price" class="filter-input" min="0">
            </div>
            <div>
                <label for="max-price">Max Price:</label>
                <input type="number" id="max-price" class="filter-input" min="0">
            </div>
            <button id="apply-price-filter">Apply Price Range</button>
        </div>


        <div id="product-container">
            <ul id="product-list" class="product-list">
                <?php
                foreach ($device_list as $device) {
                    $dev_id = $device['DEV_ID'];
                    $dev_name = $device['DEV_NAME'];
                    $cate_id = $device['CATE_ID'];
                    $brand_id = $device['BRAND_ID'];
                    $dev_image = $device['DEV_IMAGEURL'];
                    $dev_price = $device['DEV_PRICE'];
                    $img_directory = "images/";

                    // Lấy tên thương hiệu từ danh sách thương hiệu
                    $brand_name = "";
                    foreach ($brand_list as $brand) {
                        if ($brand['BRAND_ID'] == $brand_id) {
                            $brand_name = $brand['BRAND_NAME'];
                            break;
                        }
                    }

                    // Lấy tên danh mục từ danh sách danh mục
                    $cate_name = "";
                    foreach ($cate_list as $category) {
                        if ($category['CATE_ID'] == $cate_id) {
                            $cate_name = $category['CATE_NAME'];
                            break;
                        }
                    }

                    $brand_name = strtoupper($brand_name);
                    switch ($brand_name) {
                        case "SONY":
                            $img_directory .= "SONY/";
                            break;
                        case "JBL":
                            $img_directory .= "JBL/";
                            break;
                        case "MARSHALL":
                            $img_directory .= "MARSHALL/";
                            break;
                        case "BOSE":
                            $img_directory .= "BOSE/";
                            break;
                        case "YAMAHA":
                            $img_directory .= "YAMAHA/";
                            break;
                    }

                    $img_directory = $img_directory . $dev_image;

                    // Hiển thị một mục món hàng trong danh sách
                    echo "<li class='product-item'>";
                    echo "<a class='product-link' href='product_detail.php?DEV_ID=" . urlencode($dev_id) . "'>";
                    echo "<img class='product-img' src='" . htmlspecialchars($img_directory) . "' alt='" . htmlspecialchars($dev_name) . "'>";
                    echo "</a>";
                    echo "<h3><a class='product-name' href='product_detail.php?DEV_ID=" . urlencode($dev_id) . "'>" . htmlspecialchars($dev_name) . "</a></h3>";
                    echo "<p class='product-cate hidden' data-cate='" . htmlspecialchars($cate_name) . "'>Category: " . htmlspecialchars($cate_name) . "</p>";
                    echo "<p class='product-brand hidden' data-brand='" . htmlspecialchars($brand_name) . "'>Brand: " . htmlspecialchars($brand_name) . "</p>";
                    echo "<p class='product-price' data-price='" . htmlspecialchars($dev_price) . "'>Giá: " . htmlspecialchars($dev_price) . " VND</p>";


                    // Form for "Thêm vào giỏ hàng"
                    echo "<form action='add_to_cart.php' method='POST' class='buy-now-form'>";
                    echo "<input type='hidden' name='product_id' value='" . urlencode($dev_id) . "'>";
                    echo "<input type='hidden' name='quantity' value='1'>";
                    echo "<button type='submit' class='product-buy-now'>";
                    echo "<span>Thêm vào giỏ hàng</span>";
                    echo "</button>";
                    echo "</form>";

                    echo "</li>";
                }
                ?>
            </ul>
        </div>

    </div>
    <br>
    <br>

    <section id="reviews-section">
        <h2>Đánh giá cửa hàng</h2>
        <div class="reviews-container">
            <div class="review">
                <img src="images/0fd3416c.jpeg" alt="Reviewer Image" class="reviewer-image">
                <p class="reviewer-name">Jayla H.<br>@wordpress</p>
                <p class="review-text">“Starting my role as a WordPress administrator has been a joy, thanks to its intuitive interface, media management, security, and plugin integration, making websites a breeze.”</p>
            </div>
            <div class="review">
                <img src="images/68f64b9d.jpeg" alt="Reviewer Image" class="reviewer-image">
                <p class="reviewer-name">Hope D.<br>@wordpress</p>
                <p class="review-text">“Starting my role as a WordPress administrator has been a joy, thanks to its intuitive interface, media management, security, and plugin integration, making websites a breeze.”</p>
            </div>
        </div>
    </section>
    <br>

    <table>
        <tr>
            <td>
                <h2>Liên hệ chúng tôi</h2>
            </td>
            <td>
                <input type="text" placeholder="Họ Tên">
            </td>
        </tr>
        <tr>
            <td>
                <p>(028) 4569-7890</p>
                <p>help@nhom4mail.com</p>
            </td>
            <td>
                <input type="text" placeholder="Email">
            </td>
        </tr>
        <tr>
            <td>
                <p>Số 123, Đường Lê Lợi, Phường Bến Nghé,
                    Quận 1, TP. Hồ Chí Minh, Việt Nam</p>
            </td>
            <td>
                <input type="text" placeholder="Lời nhắn">
            </td>
        </tr>
    </table>
    <br> <br> <br>

</body>

<footer>
    <p>&copy; 2024 CỬA HÀNG ÂM THANH NHÓM 4. All rights reserved.</p>
</footer>

</html>