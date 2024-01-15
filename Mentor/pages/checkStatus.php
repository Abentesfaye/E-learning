<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> <!-- Add your stylesheet link here -->
    <title>Check Your Account Status - Mettu University E-Learning</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #27ae60; /* Green background color */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 40%;
            text-align: center;
            box-sizing: border-box;
        }

        .container h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #666;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .check-status-button {
            background-color: #2ecc71; /* Darker green button color */
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .check-status-button:hover {
            background-color: #27ae60; /* Lighter green on hover */
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Check Your Account Status</h2>
    <form action="#" method="post"> <!-- Replace "#" with your actual form action -->
        <div class="form-group">
            <label for="accountNumber">Account Number</label>
            <input type="text" id="accountNumber" name="accountNumber" placeholder="Enter your account number" required>
        </div>
        <button type="submit" class="check-status-button">Check Status</button>
    </form>
</div>

</body>
</html>
