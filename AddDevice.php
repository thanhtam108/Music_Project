<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Add Device</title>
    <style>
        h1 {
            text-align: center;
            color: #3c2a1e;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #FDF3C4;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #000;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input[type="submit"] {
            background-color: #62544A;
            color: white;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #ffd700;
        }
    </style>
</head>


<body>
    <h1> ADD NEW DEVICE </h1>
    <form action="AddingHandle.php" method="post" enctype="multipart/form-data" class="container">
        <div class="form-group">
            <label>Name of device:</label>
            <input type="text" name="dev_name" required>
        </div>
        <div class="form-group">
            <label>Category:</label>
            <select name="cate_id" required>
                <?php
                include "Device.php";
                $device = new Device();
                $categories = $device->get_cate_list();
                if ($categories === false) {
                    echo "<option value=''>Error retrieving categories</option>";
                } else {
                    foreach ($categories as $category) {
                        echo "<option value='" . $category['CATE_ID'] . "'>" . $category['CATE_NAME'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Brand:</label>
            <select name="brand_id" required>
                <?php
                $brands = $device->get_brand_list();

                foreach ($brands as $brand) {
                    echo "<option value='" . $brand['BRAND_ID'] . "'>" . $brand['BRAND_NAME'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Image of device:</label>
            <input type="file" name="dev_image" accept="image/*" required>
        </div>
        <div class="form-group">
            <label>Price:</label>
            <input type="text" name="dev_price" required>
        </div>
        <div class="form-group">
            <input type="submit" name="add_device" value="Add Device">
            <input type="reset" value="Reset">
        </div>
    </form>
    <a href="employee_dashboard.php">Trở về</a>
</body>

</html>