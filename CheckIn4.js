document.addEventListener("DOMContentLoaded", function () {
    checkPhone();
    checkName();
    checkEmail();
    checkAddress();
    checkAccount();
    checkPass();
    checkPassConfirm();

    document.getElementById('signupButton').addEventListener('click', function (event) {
        if (!validateForm()) {
            event.preventDefault();
        }
    });

    function validateForm() {
        var errorMessages = document.querySelectorAll('[id$="-error-msg"]');
        var inputFields = document.querySelectorAll('input');
        for (var i = 0; i < errorMessages.length; i++) {
            if (errorMessages[i].textContent !== '') {
                return false;
            }
        }
        for (var j = 0; j < inputFields.length; j++) {
            if (inputFields[j].value.trim() === '') {
                return false;
            }
        }
        return true;
    }

    // Check phone
    function checkPhone() {
        var phoneInput = document.getElementById('phone');
        var phoneRegex = /^(84|0)?[0-9]{9,10}$/;
        phoneInput.addEventListener('input', function () {
            var phoneValue = phoneInput.value.trim();
            if (phoneValue === '') {
                document.getElementById('phone-error-msg').textContent = 'Vui lòng nhập số điện thoại.';
            } else if (!phoneRegex.test(phoneValue)) {
                document.getElementById('phone-error-msg').textContent = 'Số điện thoại chưa hợp lệ.';
            } else {
                document.getElementById('phone-error-msg').textContent = '';
                checkFieldExistence('phone', phoneValue);
            }
        });
    }

    // Check name
    function checkName() {
        var nameInput = document.getElementById('name');
        nameInput.addEventListener('input', function () {
            var nameValue = nameInput.value.trim();
            if (nameValue === '') {
                document.getElementById('name-error-msg').textContent = 'Vui lòng nhập tên.';
            } else {
                document.getElementById('name-error-msg').textContent = '';
            }
        });
    }

    // Check email
    function checkEmail() {
        var emailInput = document.getElementById('email');
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        emailInput.addEventListener('input', function () {
            var emailValue = emailInput.value.trim();
            if (emailValue === '') {
                document.getElementById('email-error-msg').textContent = 'Vui lòng nhập email.';
            } else if (!emailRegex.test(emailValue)) {
                document.getElementById('email-error-msg').textContent = 'Email chưa hợp lệ.';
            } else {
                document.getElementById('email-error-msg').textContent = '';
                checkFieldExistence('email', emailValue);
            }
        });
    }

    // Check address
    function checkAddress() {
        var addressInput = document.getElementById('address');
        addressInput.addEventListener('input', function () {
            var addressValue = addressInput.value.trim();
            if (addressValue === '') {
                document.getElementById('address-error-msg').textContent = 'Vui lòng nhập địa chỉ.';
            } else {
                document.getElementById('address-error-msg').textContent = '';
            }
        });
    }

    // Check account
    function checkAccount() {
        var accountInput = document.getElementById('account');
        accountInput.addEventListener('input', function () {
            var accountValue = accountInput.value.trim();
            if (accountValue === '') {
                document.getElementById('account-error-msg').textContent = 'Vui lòng nhập tài khoản.';
            } else {
                document.getElementById('account-error-msg').textContent = '';
                checkFieldExistence('account', accountValue);
            }
        });
    }

    // Check password
    function checkPass() {
        var passInput = document.getElementById('password');
        passInput.addEventListener('input', function () {
            var passValue = passInput.value.trim();
            if (passValue === '') {
                document.getElementById('pass-error-msg').textContent = 'Vui lòng nhập mật khẩu.';
            } else {
                document.getElementById('pass-error-msg').textContent = '';
            }
        });

        passInput.addEventListener('focus', function () {
            document.getElementById('pass-error-msg').textContent = '';
        });
    }

    // Check password confirm
    function checkPassConfirm() {
        var passInput = document.getElementById('password');
        var confirmPassInput = document.getElementById('confirm_password');
        var passErrorMsg = document.getElementById('pass-error-msg');

        confirmPassInput.addEventListener('input', function () {
            var passValue = passInput.value.trim();
            var confirmPassValue = confirmPassInput.value.trim();

            if (confirmPassValue === '') {
                passErrorMsg.textContent = 'Vui lòng nhập mật khẩu xác nhận.';
            } else if (confirmPassValue !== passValue) {
                passErrorMsg.textContent = 'Mật khẩu xác nhận chưa đúng.';
            } else {
                passErrorMsg.textContent = '';
            }
        });
    }

    // Common function to check field existence via AJAX
    function checkFieldExistence(fieldName, fieldValue) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'check_field.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.responseText === fieldName + 'Exists') {
                document.getElementById(fieldName + '-error-msg').textContent = fieldName.charAt(0).toUpperCase() + fieldName.slice(1) + ' đã tồn tại.';
            } else {
                document.getElementById(fieldName + '-error-msg').textContent = '';
            }
        };
        xhr.send(fieldName + '=' + fieldValue);
    }
});
