<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách nhân viên</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        h1 {
            text-align: center;
            padding: 20px;
            background-color: #343a40;
            color: #fff;
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #343a40;
            color: #fff;
        }

        .delete-button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <h1>Danh sách nhân viên</h1>
    <?php
    include 'DBconnection.php';

    $dbConnection = new DBConnection();
    $conn = $dbConnection->conn;

    $sql = "SELECT * FROM T_EMPLOYEE WHERE EMP_ACCOUNT NOT LIKE '%admin%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Tên</th><th>Số điện thoại</th><th>Email</th><th>Tài khoản</th><th>Hành động</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['EMP_ID']) . "</td>";
            echo "<td>" . htmlspecialchars($row['EMP_NAME']) . "</td>";
            echo "<td>" . htmlspecialchars($row['EMP_PHONE']) . "</td>";
            echo "<td>" . htmlspecialchars($row['EMP_EMAIL']) . "</td>";
            echo "<td>" . htmlspecialchars($row['EMP_ACCOUNT']) . "</td>";
            echo "<td>
                    <form action='delete_emp.php' method='POST' onsubmit='return confirm(\"Bạn có chắc chắn muốn xóa nhân viên này?\");'>
                        <input type='hidden' name='emp_id' value='" . htmlspecialchars($row['EMP_ID']) . "'>
                        <button type='submit' class='delete-button'>Xóa</button>
                    </form>
                </td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Không có nhân viên nào.</p>";
    }

    $conn->close();
    ?>
</body>

</html>
