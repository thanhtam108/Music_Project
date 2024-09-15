<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Remove Device Handle</title>
</head>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("Device.php");
    $device = new Device();

    $dev_id = $_POST["DeviceIDDelete"];
    echo ($dev_id);
    $device->remove_device($dev_id);
}
?>

<body>
    <p> <a href="RemoveDevice.php"> Back </a> </p>
</body>

</html>