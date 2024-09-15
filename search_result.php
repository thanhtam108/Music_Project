<?php
include 'DBConnection.php';
$dbConnection = new DBConnection();
$conn = $dbConnection->conn;

if (isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    $searchTerm = "%" . $conn->real_escape_string($searchTerm) . "%";

    $query = "SELECT * FROM T_DEVICE WHERE DEV_NAME LIKE ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Kết quả tìm kiếm</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
    <style>
        .product-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
        }

        .product-item {
            border: 1px solid #ddd;
            margin: 10px;
            padding: 10px;
            width: 200px;
            text-align: center;
        }

        .product-img {
            width: 100%;
            height: auto;
        }

        .product-link {
            text-decoration: none;
            color: inherit;
        }

        .product-name {
            font-size: 1.2em;
            margin: 10px 0;
        }

        .product-price {
            color: #888;
        }
    </style>
</head>

<body>
    <h1>Kết quả tìm kiếm cho: <?php echo htmlspecialchars($_GET['search']); ?></h1>
    <?php if ($result->num_rows > 0) : ?>
        <ul class="product-list">
            <?php while ($row = $result->fetch_assoc()) : 
                $brand_name = strtoupper($row['BRAND_ID']);
                switch ($brand_name) {
                    case 1:
                        $img_directory = "SONY/";
                        break;
                    case 2:
                        $img_directory = "JBL/";
                        break;
                    case 3:
                        $img_directory = "MARSHALL/";
                        break;
                    case 4:
                        $img_directory = "BOSE/";
                        break;
                    case 5:
                        $img_directory = "YAMAHA/";
                        break;
                }
                ?>
                <li class="product-item">
                    <a class="product-link" href="product_detail.php?DEV_ID=<?php echo urlencode($row['DEV_ID']); ?>">
                        <img class="product-img" src="images/<?php echo $img_directory . htmlspecialchars($row['DEV_IMAGEURL']); ?>" alt="<?php echo htmlspecialchars($row['DEV_NAME']); ?>">
                    </a>
                    <h3><a class="product-name" href="product_detail.php?DEV_ID=<?php echo urlencode($row['DEV_ID']); ?>"><?php echo htmlspecialchars($row['DEV_NAME']); ?></a></h3>
                    <p class="product-price"><?php echo htmlspecialchars($row['DEV_PRICE']); ?> VND</p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else : ?>
        <p>Không tìm thấy sản phẩm nào.</p>
    <?php endif; ?>
    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>

</html>