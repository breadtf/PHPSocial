<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup</title>
</head>
<?php
// Check if setup has already run
$userFile = json_decode(file_get_contents('db/login.json'), true);

$userFile[] = $userFile;
if ($userFile[0] != ""){
    header("Location: ../index.php");
}
?>
<body>
    <h1>Welcome to PHPSocial setup!</h1>
    <p>This setup guide will guide you through setting up PHPSocial.</p>
    <h3>First, enter a username and password</h3>
    <p>This will be used for the admin account, so pick something secure</p>
    <form method="POST" action="page2.php">
        <input type="text" placeholder="Username" name="username"></input>
        <input type="password" placeholder="Password" name="password"></input><br><br>
        <input type="submit" value="Next"></input>
    </form>
</body>
</html>
