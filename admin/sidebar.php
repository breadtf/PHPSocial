<?php
// If adminEnabled is false, redirect to home page
// This is here because every admin page has the sidebar
if ($configs["adminEnabled" === false){
    header("Location: ../index.php");
}
?>
<div class="admin-sidebar">
    <h1><a href="/"><?php echo $configs["sitename"] ?></a></h1>
    <a href="index.php">Message logs</a><br>
    <a href="logs.php">View logs</a>
</div>
