<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup</title>
</head>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Load existing user data
    $users = json_decode(file_get_contents('../db/login.json'), true);

    // Check if username already exists
    if (isset($users[$username])) {
        echo "Username already exists. Please choose a different username.";
        exit;
    }

    // Add new user to the user data
    $users[$username] = [
        'password' => $hashedPassword,
        'admin' => true
    ];

    // Save the updated user data
    file_put_contents('../db/login.json', json_encode($users));
}
?>
<body>
    <h1>Finished!</h1>
    <p>Thanks for installing PHPSocial!</p>
    <a href="/">Login</a>
</body>
</html>