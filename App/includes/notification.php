<?php
function showNotification($errorMsg)
{
    if ($errorMsg) {
        echo '<style>
            .notificationtext {
                position: fixed;
                bottom: 20px;
                right: 20px;
                padding: 15px;
                background-color: #e74c3c; /* Red color */
                color: #fff;
                border-radius: 5px;
                display: none;
                animation: slideIn 0.5s ease-in-out;
            }

            @keyframes slideIn {
                from {
                    transform: translateY(100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        </style>';

        echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                const notification = document.getElementById("notificationtext");
                const message = "' . $errorMsg . '";

                if (message) {
                    showNotification(message);
                }

                function showNotification(message) {
                    // Show the notification
                    notification.innerText = message;
                    notification.style.display = "block";

                    // Hide the notification after 5 seconds
                    setTimeout(function () {
                        notification.style.display = "none";
                    }, 3000);
                }
            });
        </script>';
    }
}

function showGoodNotification($successMsg)
{
    if ($successMsg) {
        echo '<style>
            .notificationtext {
                position: fixed;
                bottom: 20px;
                right: 20px;
                padding: 15px;
                background-color: #2ecc71; /* Green color */
                color: #fff;
                border-radius: 5px;
                display: none;
                animation: slideIn 0.5s ease-in-out;
            }

            @keyframes slideIn {
                from {
                    transform: translateY(100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        </style>';

        echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                const notificationtext = document.getElementById("notificationtext");
                const message = "' . $successMsg . '";

                if (message) {
                    showNotification(message);
                }

                function showNotification(message) {
                    // Show the notification
                    notificationtext.innerText = message;
                    notificationtext.style.display = "block";

                    // Hide the notification after 5 seconds
                    setTimeout(function () {
                        notificationtext.style.display = "none";
                    }, 3000);
                }
            });
        </script>';
    }
}
?>
