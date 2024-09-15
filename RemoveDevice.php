<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Remove device</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
    <script>
        function confirmDelete() {
            var result = confirm("Do you want to delete this device?");
            return result;
        }
    </script>
</head>

<body>
    <a href="employee_dashboard.php">Trở về</a>
    <ul id="product-list" class="product-list">
        <?php
        include "Device.php";
        $device = new Device();

        $device_list = $device->get_device_list();
        $brand_list = $device->get_brand_list();
        $cate_list = $device->get_cate_list();

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
            echo "<img class='product-img' src='$img_directory' alt='$dev_name'>";
            echo "<h3>$dev_name</h3>";
            // echo "<p class='product-cate' data-cate='$cate_name'>Category: $cate_name</p>";
            // echo "<p class='product-brand' data-brand='$brand_name'>Brand: $brand_name</p>";
            echo "<p class='product-price' data-price='$dev_price'>Price: $dev_price</p>";
            echo "<form name='delete-device' action='RemovingHandle.php' method='post' onsubmit='return confirmDelete()'>
            <input type='hidden' name='DeviceIDDelete' value='" . $dev_id . "' />
            <input type='submit' name='deleteDev' value='Xoá sản phẩm' /></form>";
            echo "</li>";
        }
        ?>
    </ul>
</body>

</html>