<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs
    $errors = array();

    // Check if an image is uploaded
    if (empty($_FILES["dev_image"]["name"]) && !$_FILES['tree_pic']['error'] === UPLOAD_ERR_OK) {
        $errors[] = "Image is required";
    }

    // Validate price
    if (!isset($_POST['dev_price']) || !is_numeric($_POST['dev_price'])) {
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


    include "Device.php";
    $device = new Device();
    echo ($_POST['dev_id']);
    $dev_info = $device->get_device($_POST['dev_id']);

    if ($dev_info) echo ("OK");
    $img_directory = "images/";

    $brand_name =  $dev_info['BRAND_NAME'];
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

    $old_directory = $img_directory . $dev_info['DEV_IMAGEURL'];


    // Delete the old image of the device
    if (file_exists($old_directory)) {
        unlink($old_directory);
        echo "<p>Old image deleted successfully</p>";
    } else {
        echo "<p>Old image does not exist</p>";
    }

    // update new information of device

    $new_dev_price = $_POST["dev_price"];

    $pic_name = $_FILES['dev_image']['name'];
    $file_tmp_name = $_FILES['dev_image']['tmp_name'];
    $file_size = $_FILES['dev_image']['size'];
    $file_type = $_FILES['dev_image']['type'];

    $target_file = $img_directory . basename($pic_name);

    if (move_uploaded_file($file_tmp_name, $target_file)) {
        echo "<p> Upload image successfully <p>";
    } else {
        echo "Error when uploading image.";
    }

    $device->update_device($_POST["dev_id"], $_POST["dev_name"], $_POST["cate_id"], $_POST["brand_id"], $pic_name, $new_dev_price);
    echo "<a href='employee_dashboard.php'>Trở về</a>";
} else {
    // If the form is not submitted via POST method, redirect the user back to the form
    header("Location: UpdateDevice.php");
    exit;
}
