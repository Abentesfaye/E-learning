<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Request Form</title>
    <link rel="icon" type="logo" href="../../assets/logo/logo.png" />
    <link rel="stylesheet" href="../../font.css">
    <link rel="stylesheet" href="../style/request.css">
</head>
<body>

<div class="mentor-form">
    <img src="../../assets/logo/logo.png" alt="Mentor Logo" class="logo">
    <h2> Become A  Mentor <br> Request Form</h2>
    <form action="#" method="post"> 
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" required>
        </div>
        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" required>
        </div>
        <div class="form-group">
            <label for="phoneNumber">Phone Number</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Enter your phone number" required>
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email address" required>
        </div>
        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>
        </div>
        <div class="form-group">
            <label for="education">Education Information</label>
            <textarea id="education" name="education" placeholder="Enter your education information" required></textarea>
        </div>
        <div class="form-group">
            <label for="interests">Interests</label>
            <input type="text" id="interests" name="interests" placeholder="Enter your interests" required>
        </div>
        <div class="form-group">
            <label for="educationDoc">Upload Education Document</label>
            <input type="file" id="educationDoc" name="educationDoc" accept=".pdf, .doc, .docx" required>
        </div>
        <div class="form-group">
            <label for="whyMentor">Why do you want to become a mentor? (Write an essay)</label>
            <textarea id="whyMentor" name="whyMentor" placeholder="Write an essay explaining why you want to become a mentor" required></textarea>
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="idProof">Upload your ID for KYC</label>
            <input type="file" id="idProof" name="idProof" accept=".pdf, .jpg, .jpeg, .png" required>
        </div>
        <!-- Add additional form fields here -->
        <button type="submit" class="submit-button">Submit Request</button>
    </form>
</div>
 <!-- Add the notification element -->
 <div id="notification" class="notification"></div>
 <script src="../clientSide/requestvalidation.js"></script>
</body>
</html>
