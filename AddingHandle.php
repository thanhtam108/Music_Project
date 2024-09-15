<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs
    $errors = array();

    // Check if the device name is provided
    if (empty($_POST["dev_name"])) {
        $errors[] = "Device name is required";
    }

    // Validate category ID
    if (!isset($_POST["cate_id"]) || !is_numeric($_POST["cate_id"])) {
        $errors[] = "Invalid category ID";
    }

    // Validate brand ID
    if (!isset($_POST["brand_id"]) || !is_numeric($_POST["brand_id"])) {
        $errors[] = "Invalid brand ID";
    }

    // Check if an image is uploaded
    if (empty($_FILES["dev_image"]["name"]) && !$_FILES['tree_pic']['error'] === UPLOAD_ERR_OK) {
        $errors[] = "Image is required";
    }

    // Validate price
    if (!isset($_POST["dev_price"]) || !is_numeric($_POST["dev_price"])) {
        $errors[] = "Invalid price";
    }

    // If there are errors, display them and redirect back to the form
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p>Error: $error</p>";
        }
        echo "<a href='javascript:history.back()'>Go back</a>";
        exit;
    }

    // Adding the device into the database
    include "Device.php";
    $device = new Device();

    $dev_name = $_POST["dev_name"];
    $brand_id = $_POST["brand_id"];
    $cate_id = $_POST["cate_id"];
    $dev_price = $_POST["dev_price"];

    $pic_name = $_FILES['dev_image']['name'];
    $file_tmp_name = $_FILES['dev_image']['tmp_name'];
    $file_size = $_FILES['dev_image']['size'];
    $file_type = $_FILES['dev_image']['type'];

    $upload_directory = "images/";
    $brand_name = $device->get_brand($brand_id);
    $brand_name = strtoupper($brand_name);
    switch ($brand_name){
        case "SONY":
            $upload_directory .= "SONY/";
            break;
        case "JBL":
            $upload_directory .= "JBL/";
            break;
        case "MARSHALL":
            $upload_directory .= "MARSHALL/";
            break;
        case "BOSE":
            $upload_directory .= "BOSE/";
            break;
        case "YAMAHA":
            $upload_directory .= "YAMAHA/";
            break;
    }
    $target_file = $upload_directory . basename($pic_name);

    if (move_uploaded_file($file_tmp_name, $target_file)) {
        echo "<p> Upload image successfully <p>";
    } else {
        echo "Error when uploading image.";
    }

    $device->add_device($dev_name, $brand_id, $cate_id, $pic_name, $dev_price);
    
    // Redirect the user to a success page or display a success message
    echo "Device added successfully!";
    echo "<a href='AddDevice.php'>Trở về</a>";
} else {
    // If the form is not submitted via POST method, redirect the user back to the form
    header("Location: AddDevice.php");
    exit;
}
