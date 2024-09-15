<!DOCTYPE html>
<html lang="en">

<head>
    <title>UPDATE DEVICE INFORMATION</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
    <style>
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

        .form-table {
            width: 100%;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #000;
        }

        .form-field input,
        .form-field select {
            width: calc(100% - 10px);
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input[type="file"] {
            width: calc(100% - 12px);
        }

        .form-group input[type="submit"] {
            background-color: #ffd700;
            color: #000;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #ffcc00;
        }
    </style>
</head>

<body>
    <header>
        <h1>UPDATE DEVICE INFORMATION</h1>
    </header>

    <?php
    include "Device.php";
    $device = new Device();
    $dev_result = $device->get_device($_POST['DeviceIDUpdate']);
    ?>

    <form name="update" method="post" action="UpdateHandle.php" enctype="multipart/form-data" class="container">
        <table class="form-table">
            <tr class="form-group">
                <td class="form-label"> Device ID </td>
                <td class="form-field">
                    <input type="text" name="dev_id" value="<?php echo $dev_result['DEV_ID']; ?>" disabled="disabled" />
                    <input type="hidden" name="dev_id" value="<?php echo $dev_result['DEV_ID']; ?>" />
                </td>
            </tr>
            <tr class="form-group">
                <td class="form-label"> Device Name </td>
                <td class="form-field">
                    <input type="text" name="dev_name" value="<?php echo $dev_result['DEV_NAME']; ?>" />
                </td>
            </tr>
            <tr class="form-group">
                <td class="form-label"> Brand </td>
                <td class="form-field">
                    <select name="brand_id" required>
                        <?php
                        $brands = $device->get_brand_list();

                        foreach ($brands as $brand) {
                            echo "<option name='brand_id' value='" . $brand['BRAND_ID'] . "'>" . $brand['BRAND_NAME'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="form-group">
                <td class="form-label"> Category </td>
                <td class="form-field">
                    <select name="cate_id" required>
                        <?php
                        $categories = $device->get_cate_list();
                        if ($categories === false) {
                            echo "<option value=''>Error retrieving categories</option>";
                        } else {
                            foreach ($categories as $category) {
                                echo "<option name='cate_id' value='" . $category['CATE_ID'] . "'>" . $category['CATE_NAME'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="form-group">
                <td class="form-label"> Price </td>
                <td class="form-field">
                    <input type="text" name="dev_price" value="" />
                </td>
            </tr>
            <tr class="form-group">
                <td class="form-label">New image of device: </td>
                <td class="form-field"><input type="file" name="dev_image" accept="image/*" required></td>
            </tr>
            <tr class="form-group">
                <td colspan="2" align="center">
                    <input type="submit" name="save" value="SAVE UPDATES" />
                </td>
            </tr>
        </table>
    </form>
</body>

</html>