<?php
session_start();
include 'DBconnection.php';
$dbConnection = new DBConnection();
$conn = $dbConnection->conn;
$acc = $_SESSION['loggedCUS_in'];
$query = "SELECT * FROM T_CUSTOMER WHERE CUSTOMER_ACCOUNT = '$acc'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['CUSTOMER_NAME'];
    $email = $row['CUSTOMER_EMAIL'];
    $address = $row['CUSTOMER_ADDRESS'];
    $account = $row['CUSTOMER_ACCOUNT'];
    $phone = $row['CUSTOMER_PHONE'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/favicon.jpg">
    <script src="CheckIn4.js"></script>
    <title>Thông tin tài khoản</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
    <style>
        .container {
            position: absolute;
            width: 400px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .container h2 {
            text-align: center;
            color: #333;
        }

        .container table {
            width: 100%;
        }

        .container table td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .container table td:first-child {
            width: 30%;
            font-weight: bold;
            color: #555;
        }

        .container .cancel-button {
            padding: 10px 20px;
            margin-right: auto;
            /* Canh giữa trái */
            margin-left: 10px;
            /* Khoảng cách với nút "Edit Information" */
            background-color: #ccc;
            color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .container .cancel-button:hover {
            background-color: #bbb;
        }

        .container .edit-button {
            padding: 10px 20px;
            margin-left: auto;
            /* Canh giữa phải */
            margin-right: 10px;
            /* Khoảng cách với nút "Cancel" */
            background-color: rgb(20, 79, 95);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
        }

        .modal-content h4 {
            margin-top: 0;
        }

        .save-button,
        .cancel-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin: 10px 5px;
            transition: background-color 0.3s;
        }

        .save-button:hover {
            background-color: #218838;
        }

        .cancel-button:hover {
            background-color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your Information &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2>
        <br /><br />
        <table>
            <tr>
                <td><strong>Name:</strong></td>
                <td id="name"><?php echo isset($name) ? $name : ''; ?></td>
                <td><button id="edit-name" class="edit-button" data-field="name">Edit</button></td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td id="email"><?php echo isset($email) ? $email : ''; ?></td>
                <td><button id="edit-email" class="edit-button" data-field="email">Edit</button></td>
            </tr>
            <tr>
                <td><strong>Address:</strong></td>
                <td id="address"><?php echo isset($address) ? $address : ''; ?></td>
                <td><button id="edit-address" class="edit-button" data-field="address">Edit</button></td>
            </tr>
            <tr>
                <td><strong>User name:</strong></td>
                <td id="username"><?php echo isset($account) ? $account : ''; ?></td>
                <td><button id="edit-username" class="edit-button" data-field="username">Edit</button></td>
            </tr>
            <tr>
                <td><strong>Phone:</strong></td>
                <td id="phone"><?php echo isset($phone) ? $phone : ''; ?></td>
                <td><button id="edit-phone" class="edit-button" data-field="phone">Edit</button></td>
            </tr>
        </table>
        <button class="cancel-button" onclick="window.location.href = 'index.php';">Đóng</button>
        <div id="editModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h4>Chỉnh sửa <span id="fieldTitle"></span></h4>
                <input type="text" id="editInput" name="editInput">
                <button id="saveButton" class="save-button">Lưu</button>
                <button id="cancelButton" class="cancel-button" onclick="closeModal()">Hủy</button>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var editButtons = document.querySelectorAll("[id^='edit-']");
                editButtons.forEach(function(button) {
                    button.addEventListener("click", function() {
                        var fieldName = this.getAttribute("data-field");
                        openModal(fieldName);
                    });
                });
            });

            function openModal(field) {
                document.getElementById('editModal').style.display = 'block';
                document.getElementById('fieldTitle').textContent = field.charAt(0).toUpperCase() + field.slice(1);
                document.getElementById('editInput').value = document.getElementById(field).textContent.trim();
                document.getElementById('saveButton').setAttribute('data-field', field);
            }

            function closeModal() {
                document.getElementById('editModal').style.display = 'none';
            }

            document.getElementById('saveButton').addEventListener('click', function() {
                var field = this.getAttribute('data-field');
                var newValue = document.getElementById('editInput').value;

                // Update the UI with the new value
                document.getElementById(field).textContent = newValue;
                closeModal();

                // Send the updated value to the server via AJAX
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_user_info.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log(xhr.responseText);
                    }
                };
                xhr.send('field=' + encodeURIComponent(field) + '&value=' + encodeURIComponent(newValue));
            });
        </script>
</body>

</html>