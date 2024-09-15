<?php
include 'DBConnection.php';
$dbConnection = new DBConnection();
$conn = $dbConnection->conn;

include "Device.php";
$device = new Device();

$device_list = $device->get_device_list();
$brand_list = $device->get_brand_list();
$cate_list = $device->get_cate_list();

if (isset($_GET['DEV_ID'])) {
    $dev_id = $_GET['DEV_ID'];

    $stmt = $conn->prepare("SELECT * FROM T_DEVICE WHERE DEV_ID = ?");
    $stmt->bind_param("i", $dev_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $dev_name = htmlspecialchars($row['DEV_NAME']);
        $dev_price = htmlspecialchars($row['DEV_PRICE']);
        $cate_id = htmlspecialchars($row['CATE_ID']);
        $brand_id = htmlspecialchars($row['BRAND_ID']);
        $dev_image = htmlspecialchars($row['DEV_IMAGEURL']);
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
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "No product ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $dev_name; ?></title>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        .product-detail-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            padding: 20px;
            margin: 20px;
            text-align: center;
        }

        .product-detail-img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .product-detail-price {
            font-size: 20px;
            color: #e74c3c;
            margin-bottom: 10px;
        }

        .product-detail-description {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        button.product-addtoCart {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button.product-addtoCart:hover {
            background-color: #0056b3;
        }

        button.product-addtoCart a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="product-detail-container">
        <img class="product-detail-img" src="<?php echo $img_directory; ?>" alt="<?php echo $dev_name; ?>">
        <h1><?php echo $dev_name; ?></h1>
        <p class="product-detail-price"><?php echo $dev_price; ?> VND</p>
        <p class="product-detail-description">Thương hiệu: <?php echo $brand_name; ?></p>
        <p>Danh mục: <?php echo $cate_name; ?> </p>
        <button class="product-addtoCart">
            <a href="purchase_page.php?DEV_ID=<?php echo urlencode($dev_id); ?>&DEV_NAME=<?php echo urlencode($dev_name); ?>&DEV_PRICE=<?php echo urlencode($dev_price); ?>&IMG_IMAGEURL=<?php echo urlencode($img_directory); ?>">
                <span>Mua Hàng</span>
            </a>
        </button>
        <a href="index.php">Trở về</a>
    </div>
</body>

</html>