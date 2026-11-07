
<?php
include "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST["fullname"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    if ($password != $confirm) {
        $error = "Passwords do not match.";

    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";

    } else {

        $check = $conn->prepare(
            "SELECT id FROM users WHERE username = ?"
        );

        $check->bind_param("s", $username);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already exists.";

        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $role = "user";
            $status = "pending";

            $stmt = $conn->prepare(
                "INSERT INTO users
                (fullname, username, password, role, status)
                VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sssss",
                $fullname,
                $username,
                $hashed_password,
                $role,
                $status
            );

            if ($stmt->execute()) {
                header("Location: pending.php");
                exit();
            } else {
                $error = "Registration failed.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>JAS ONLINE SHOP | Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>JAS ONLINE SHOP</h1>
    <h2>Create Account</h2>

    <?php if ($error != ""): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Full Name</label>
        <input type="text" name="fullname" required>

        <label>Username</label>
        <input type="text" name="username" minlength="4" required>

        <label>Password</label>
        <input type="password" name="password" minlength="6" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Register</button>

    </form>

    <p>Already have an account?
        <a href="index.php">Sign In</a>
    </p>
</div>

</body>
</html>
