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
session_destroy();

// Redirect the user to the login page
header('Location: index.php');
exit;