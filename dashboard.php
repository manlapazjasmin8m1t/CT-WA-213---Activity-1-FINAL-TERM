
<?php
include "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>JAS ONLINE SHOP | Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>JAS ONLINE SHOP</h1>

    <h2>Welcome,
        <?php echo htmlspecialchars($_SESSION["fullname"]); ?>!
    </h2>

    <p>You have successfully logged in.</p>

    <?php if ($_SESSION["role"] == "admin"): ?>

        <h3>Administrator Account</h3>

        <a href="users.php">Manage User Accounts</a>

    <?php else: ?>

        <h3>Customer Account</h3>

        <p>Welcome to JAS ONLINE SHOP!</p>

    <?php endif; ?>

    <br><br>

    <a href="logout.php">Logout</a>

</div>

</body>
</html>
