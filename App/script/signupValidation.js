 document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('.signup-form');
        const phoneInput = document.getElementById('phone');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const notification = document.getElementById('notification');

        form.addEventListener('submit', function (event) {
            if (!isValidPhoneNumber(phoneInput.value)) {
                showNotification('Please enter a valid Ethiopian phone number');
                event.preventDefault();
            }
            if (!isValidPassword(passwordInput.value)) {
    showNotification('Password must be more than 6 characters and include both letters and numbers');
    event.preventDefault();
}
            if (passwordInput.value !== confirmPasswordInput.value) {
                showNotification('Passwords do not match');
                event.preventDefault();
            }
        });

        function isValidPhoneNumber(phoneNumber) {
//  a valid Ethiopian phone number starts with +251 or 09, followed by 9 digits
const phoneRegex = /^(?:\+251|09)\d{8}$/;
return phoneRegex.test(phoneNumber);
}
function isValidPassword(password) {
// Password must be more than 6 characters and include both letters and numbers
const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d).{6,}$/;
return passwordRegex.test(password);
}


        function showNotification(message) {
            // Show the notification
            notification.innerText = message;
            notification.style.display = 'block';

            // Hide the notification after 3 seconds
            setTimeout(function () {
                notification.style.display = 'none';
            }, 3000);
        }
    });
