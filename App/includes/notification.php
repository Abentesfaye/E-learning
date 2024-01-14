<?php
function showNotification($errorMsg)
{
    if ($errorMsg) {
        echo '<style>
            
            .notification {
                position: fixed;
                bottom: 20px;
                right: 20px;
                padding: 15px;
                background-color: #e74c3c; /* Red color */
                color: #fff;
                border-radius: 5px;
                display: none;
            }
        </style>';

        echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                const notification = document.getElementById("notification");
                const message = "' . $errorMsg . '";

                if (message) {
                    showNotification(message);
                }

                function showNotification(message) {
                    // Show the notification
                    notification.innerText = message;
                    notification.style.display = "block";

                    // Hide the notification after 3 seconds
                    setTimeout(function () {
                        notification.style.display = "none";
                    }, 3000);
                }
            });
        </script>';
    }
}
?>
