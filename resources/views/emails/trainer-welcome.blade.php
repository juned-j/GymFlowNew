<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Trainer Account</title>
</head>

<body>
    <h2>Welcome to PTBuddy</h2>
    <p>Hello {{ $user->name }},</p>
    <p>Your trainer account has been created successfully.</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>Please login and change your password immediately.</p>
</body>

</html>