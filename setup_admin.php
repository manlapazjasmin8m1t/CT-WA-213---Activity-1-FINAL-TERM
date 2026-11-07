
<?php
include "config.php";

$fullname = "Administrator";
$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$role = "admin";
$status = "approved";

$check = $conn->prepare(
    "SELECT id FROM users WHERE username = ?"
);

$check->bind_param("s", $username);
$check->execute();

if ($check->get_result()->num_rows > 0) {

    echo "Admin account already exists.";

} else {

    $stmt = $conn->prepare(
        "INSERT INTO users
        (fullname, username, password, role, status)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssss",
        $fullname,
        $username,
        $password,
        $role,
        $status
    );

    if ($stmt->execute()) {
        echo "Admin account created successfully!";
    } else {
        echo "Failed to create admin account.";
    }

    $stmt->close();
}

$check->close();
?>
