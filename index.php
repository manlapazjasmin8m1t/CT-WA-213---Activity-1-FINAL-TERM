
<?php
include "config.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            if ($user["status"] == "approved") {

                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["fullname"] = $user["fullname"];
                $_SESSION["role"] = $user["role"];

                header("Location: dashboard.php");
                exit();

            } elseif ($user["status"] == "pending") {
                $error = "Your account is waiting for admin approval.";

            } else {
                $error = "Your account has been rejected.";
            }

        } else {
            $error = "Incorrect username or password.";
        }

    } else {
        $error = "Account does not exist.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>JAS ONLINE SHOP | Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>JAS ONLINE SHOP</h1>
    <h2>Sign In</h2>

    <?php if ($error != ""): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Sign In</button>
    </form>

    <p>Don't have an account?
        <a href="register.php">Register here</a>
    </p>
</div>

</body>
</html>
