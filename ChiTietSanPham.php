<!-- product_detail.php -->
<?php
include 'DBConnection.php';
$dbConnection = new DBConnection();
$conn = $dbConnection->conn;

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details from the database
$stmt = $conn->prepare("SELECT name, description, price, image_url FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($name, $description, $price, $image_url);
$stmt->fetch();
$stmt->close();
?>

<html>
<head>
    <title><?php echo $name; ?></title>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
</head>
<body>
    <h1><?php echo $name; ?></h1>
    <img src="<?php echo $image_url; ?>" alt="<?php echo $name; ?>">
    <p><?php echo $description; ?></p>
    <p>Price: $<?php echo $price; ?></p>
    <a href="products.php">Back to Products</a>
</body>
</html>
