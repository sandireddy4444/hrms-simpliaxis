<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Simpliaxis HRMS</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; }
        .header { background-color: #fc544b; color: white; padding: 10px; text-align: center; }
        .details { margin-top: 20px; }
        .details p { margin: 5px 0; }
        .footer { margin-top: 20px; font-size: 0.9em; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Welcome to Simpliaxis HRMS</h2>
        </div>
        <div class="details">
            <p><strong>Dear {{ $employeeData['name'] }},</strong></p>
            <p>Your account has been created successfully. Below are your details:</p>
            <p><strong>Name:</strong> {{ $employeeData['name'] }}</p>
            <p><strong>Phone Number:</strong> {{ $employeeData['phone_no'] }}</p>
            <p><strong>Username (Email):</strong> {{ $employeeData['email'] }}</p>
            <p><strong>Password:</strong> {{ $employeeData['password'] }}</p>
            <p><strong>Role:</strong> {{ $employeeData['role'] }}</p>
            <p><strong>Created By:</strong> {{ $creatorName }}</p>
            <p>Please log in at <a href="http://127.0.0.1:8000/login">http://127.0.0.1:8000/login</a> to access your account. For security, change your password after your first login.</p>
        </div>
        <div class="footer">
            <p>This is an automated message from Simpliaxis HRMS. Do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
