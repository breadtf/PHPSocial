<?php

// Log IP address, page URL, username (if logged in), and timestamp
$requestLog = [
    'ip' => $_SERVER['REMOTE_ADDR'],
    'url' => $_SERVER['REQUEST_URI'],
    'username' => isset($_SESSION['username']) ? $_SESSION['username'] : null,
    'timestamp' => date('Y-m-d H:i:s')
];

// Load existing request logs
$requestLogs = json_decode(file_get_contents('../db/request_logs.json'), true);

// Add the new request log to the existing logs
$requestLogs[] = $requestLog;

// Save the updated request logs
file_put_contents('../db/request_logs.json', json_encode($requestLogs));
?>


<?php
$configs = include('../config.php');
session_start();

// Check if the user is an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Load messages data
$messages = json_decode(file_get_contents('../db/messages.json'), true);
$requestLogs = json_decode(file_get_contents('../db/request_logs.json'), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];

    // Remove the post
    unset($messages[$postId]);

    // Save the updated messages data
    file_put_contents('../db/messages.json', json_encode($messages));

    header("Location: admin_panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $configs["sitename"] ?> - Admin Panel</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <?php include('sidebar.php');?>
    <div class="wrapper">
        <h2>Admin Panel</h2>
        <a href="/">Back</a>
        <h3>Posts:</h3>
        <?php foreach (array_reverse($messages) as $postId => $post): ?>
            <div>
                <hr>
                <p>@<?php echo $post['author']; ?></p>
                <p><?php echo $post['content']; ?></p>
                <form method="POST" action="">
                    <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                    <input type="submit" value="Remove Post">
                </form>
            </div>
            <hr>
        <?php endforeach; ?>
        </div>
</body>
</html>
