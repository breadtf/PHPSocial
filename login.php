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
$configs = include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Load user data
    $users = json_decode(file_get_contents('db/login.json'), true);

    // Check if username exists and password is correct
    if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['admin'] = $users[$username]['admin'];

        // Redirect to the user's dashboard
        header('Location: dashboard.php');
        exit;
    }

    echo "Invalid username or password. Please try again.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $configs["sitename"] ?> - User Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <h2>User Login</h2>
    <a href="/">Home</a><br><br>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <br>
    <a href="register.php">Register</a>
</body>
</html>
