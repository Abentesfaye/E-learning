document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('form');
    const phoneInput = document.getElementById('phoneNumber');
    const emailInput = document.getElementById('email');
    const notification = document.getElementById('notification');

    form.addEventListener('submit', function (event) {
        if (!isValidPhoneNumber(phoneInput.value)) {
            showNotification('Please enter a valid Ethiopian phone number');
            event.preventDefault();
        }
        if (!isValidEmail(emailInput.value)) {
            showNotification('Please enter a valid email address');
            event.preventDefault();
        }
    });

    function isValidPhoneNumber(phoneNumber) {
        // A valid Ethiopian phone number starts with +251 or 09, followed by 9 digits
        const phoneRegex = /^(?:\+251|09)\d{8}$/;
        return phoneRegex.test(phoneNumber);
    }

    function isValidEmail(email) {
        // Validate that the email address is valid
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
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
