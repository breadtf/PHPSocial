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

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "You are not logged in.";
    exit;
}

// Load messages data
$messages = json_decode(file_get_contents('db/messages.json'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['content'])) {
        $content = $_POST['content'];

        // Generate a unique post ID
        $postId = uniqid();

        // Create a new post
        $messages[$postId] = [
            'content' => $content,
            'author' => $_SESSION['username'],
            'likes' => [] // Initialize the likes array for the new post
        ];

        // Save the updated messages data
        file_put_contents('db/messages.json', json_encode($messages));

        header('Location: dashboard.php');
        exit;
    } elseif (isset($_POST['like'])) {
        $postId = $_POST['post_id'];
        $username = $_SESSION['username'];

        // Check if the post exists and has the 'likes' key
        if (isset($messages[$postId]) && array_key_exists('likes', $messages[$postId])) {
            // Check if the user has already liked the post
            if (!in_array($username, $messages[$postId]['likes'])) {
                // Add the user to the likes array of the post
                $messages[$postId]['likes'][] = $username;

                // Save the updated messages data
                file_put_contents('db/messages.json', json_encode($messages));
            }
        }
    } elseif (isset($_POST['unlike'])) {
        $postId = $_POST['post_id'];
        $username = $_SESSION['username'];

        // Check if the post exists and has the 'likes' key
        if (isset($messages[$postId]) && array_key_exists('likes', $messages[$postId])) {
            // Check if the user has liked the post
            $likeIndex = array_search($username, $messages[$postId]['likes']);
            if ($likeIndex !== false) {
                // Remove the user from the likes array of the post
                unset($messages[$postId]['likes'][$likeIndex]);

                // Save the updated messages data
                file_put_contents('db/messages.json', json_encode($messages));
            }
        }
    } elseif (isset($_POST['logout'])) {
        // Logout user
        session_destroy();
        header('Location: index.php');
        exit;
    } elseif (isset($_POST['reply'])) {
        $postId = $_POST['post_id'];
        $content = $_POST['reply_content'];

        // Check if the post exists
        if (isset($messages[$postId])) {
            // Create a new reply for the post
            $replyId = uniqid();
            $reply = [
                'content' => $content,
                'author' => $_SESSION['username']
            ];

            // Add the reply to the post
            $messages[$postId]['replies'][$replyId] = $reply;

            // Save the updated messages data
            file_put_contents('db/messages.json', json_encode($messages));
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $configs["sitename"] ?> - Dashboard</title>
    <link rel="stylesheet" href="css/<?php echo $configs["theme"] ?>">
</head>
<body>
    <div class="header">
        <div class="login">
            <a>@<?php echo $_SESSION['username']; ?></a>
            <form method="POST" action="">
                <input type="submit" name="logout" value="Logout">
            </form>
        </div>
        <h2><?php echo $configs["sitename"] ?></h2>
    </div>
    <div class="wrapper">
        <?php
            if ($_SESSION['admin'] == true) {
                echo "<a href='admin/'>Admin panel</a>";
            }
        ?>
        <h3>Create a Post:</h3>
        <div class="createpost">
            <form method="POST" action="">
                <textarea name="content" placeholder="Enter your message here..." required class="post-textarea"></textarea><br><br>
                <input type="submit" value="Create Post">
            </form>
        </div>
        <hr>
        
        <?php foreach (array_reverse($messages) as $postId => $post): ?>
            <div>
                <p>@<?php echo $post['author']; ?></p>
                <p><?php echo $post['content']; ?></p>

                <!-- Like button -->
                <form method="POST" action="">
                    <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                    <?php if (isset($post['likes']) && in_array($_SESSION['username'], $post['likes'])): ?>
                        <input type="submit" name="unlike" value="Unlike <?php echo isset($post['likes']) ? count($post['likes']) : 0; ?>">
                    <?php else: ?>
                        <input type="submit" name="like" value="Like <?php echo isset($post['likes']) ? count($post['likes']) : 0; ?>">
                    <?php endif; ?>
                </form><br>
                 <!-- Reply form -->
                
                <div class="replies">
                    <!-- Display replies -->
                    <?php if (isset($post['replies'])): ?>
                        <?php foreach ($post['replies'] as $replyId => $reply): ?>
                            <div>
                                <p>@<?php echo $reply['author']; ?></p>
                                <p><?php echo $reply['content']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                        <textarea name="reply_content" placeholder="Reply to this post..." required class="replybox"></textarea><br>
                        <input type="submit" name="reply" value="Reply">
                    </form>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>

        <br>
    </div>
</body>
</html>
