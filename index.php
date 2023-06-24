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
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    // Redirect to the user's dashboard
    header('Location: dashboard.php');
    exit;
}

$messages = json_decode(file_get_contents('db/messages.json'), true);


?>

<!DOCTYPE html>
<html>
<head>
    <title>PHPSocial - Home</title>
    <link rel="stylesheet" href="css/light.css">
</head>
<body>
    <div class="header">
        <div class="login">
            <a href="login.php">Login</a> <a href="register.php">Register</a>
        </div>
        <h2>PHPSocial</h2>
    </div>
    <div class="wrapper">
	<!-- Bad solution, but I'm way too tired to fix it, should realy do checks before we actually try to use $messages. -->
        <?php if ($messages !== null) foreach (array_reverse($messages) as $postId => $post):?>
            <div>
                <p>@<?php if ($messages !== null) {echo $post['author'];}  ?></p>
                <p><?php echo $post['content']; ?></p>
                <p>Likes: <?php echo isset($post['likes']) ? count($post['likes']) : 0; ?></p>
            </div>
            <div class="replies">
                <?php if (isset($post['replies'])): ?>
                    <?php foreach ($post['replies'] as $replyId => $reply): ?>
                        <div>
                            <p>@<?php echo $reply['author']; ?></p>
                                <p><?php echo $reply['content']; ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <hr>
        <?php endforeach; ?>
    </div>
</body>
</html>
