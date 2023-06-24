<?php

// Log IP address, page URL, username (if logged in), and timestamp
$requestLog = [
    'ip' => $_SERVER['REMOTE_ADDR'],
    'url' => $_SERVER['REQUEST_URI'],
    'username' => isset($_SESSION['username']) ? $_SESSION['username'] : null,
    'timestamp' => date('Y-m-d H:i:s')
];

// Load existing request logs
$requestLogs = json_decode(file_get_contents('db/request_logs.json'), true);

// Add the new request log to the existing logs
$requestLogs[] = $requestLog;

// Save the updated request logs
file_put_contents('db/request_logs.json', json_encode($requestLogs));
?>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Load existing user data
    $users = json_decode(file_get_contents('db/login.json'), true);

    // Check if username already exists
    if (isset($users[$username])) {
        echo "Username already exists. Please choose a different username.";
        exit;
    }

    // Add new user to the user data
    $users[$username] = [
        'password' => $hashedPassword,
        'admin' => false
    ];

    // Save the updated user data
    file_put_contents('db/login.json', json_encode($users));

    echo "Registration successful. You can now <a href='login.php'>login</a>.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHPSocial - User Registration</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <h2>User Registration</h2>
    <a href="/">Home</a><br><br>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="submit" value="Register">
    </form>
    <br>
    <a href="login.php">Login</a>
</body>
</html>
