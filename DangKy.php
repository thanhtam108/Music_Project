<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="styleDangKy.css">
    <script src="CheckIn4.js"></script>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">

</head>

<body>
    <div class="container">
        <h2>Đăng ký</h2>
        <div class="close-button" onclick="window.location.href = 'index.php';">Close</div>
        <br /><br />
        <form action="signup_process.php" method="post">
            <div>
                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="phone" onkeydown="checkPhone()">
                <span id="phone-error-msg" class="error-msg"></span>
            </div>
            <div>
                <label for="name">Tên:</label>
                <input type="text" id="name" name="name" onkeydown="checkInput(name,name-error-msg)">
                <span id="name-error-msg" class="error-msg"></span>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" onkeydown="checkEmail()">
                <span id="email-error-msg" class="error-msg"></span>
            </div>
            <div>
                <label for="address">Địa chỉ (số nhà/xã phường/Quận/tỉnh TP):</label>
                <input type="text" id="address" name="address" onkeydown="checkAddress()">
                <span id="address-error-msg" class="error-msg"></span>
            </div>
            <div>
                <label for="account">Tên tài khoản: </label>
                <input type="text" id="account" name="account" onkeydown="checkAccount()">
                <span id="account-error-msg" class="error-msg"></span>
            </div>
            <div>
                <label for="password">Mật khẩu: </label>
                <input type="password" id="password" name="password" onkeydown="checkPass()">
                <input type="checkbox" id="show" onclick="showPass();">Show Password
                <span id="pass-error-msg" class="error-msg"></span>
            </div>
            <div>
                <label for="confirm_password">Nhập lại mật khẩu: </label>
                <input type="password" id="confirm_password" name="confirm_password" onkeydown="checkPassConfirm()">
            </div>
            <button type="submit" id="signupButton" name="signup">Đăng ký</button>
            <p>Đã có tài khoản? <a href="DangNhap.php">Đăng nhập.</a></p>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', checkPhone());
        document.addEventListener('DOMContentLoaded', checkName());
        document.addEventListener('DOMContentLoaded', checkEmail());
        document.addEventListener('DOMContentLoaded', checkAddress());
        document.addEventListener('DOMContentLoaded', checkAccount());
        document.addEventListener('DOMContentLoaded', checkPass());
        document.addEventListener('DOMContentLoaded', checkPassConfirm());
        document.getElementById('signupButton').addEventListener('click', function(event) {
            validateForm();
        });
    </script>

</body>


</html>