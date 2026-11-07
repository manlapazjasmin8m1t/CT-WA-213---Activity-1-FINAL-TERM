
<?php
include "config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: index.php");
    exit();
}

if (isset($_POST["approve"])) {

    $id = (int)$_POST["user_id"];

    $stmt = $conn->prepare(
        "UPDATE users SET status = 'approved'
         WHERE id = ? AND role = 'user'"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: users.php");
    exit();
}

if (isset($_POST["reject"])) {

    $id = (int)$_POST["user_id"];

    $stmt = $conn->prepare(
        "UPDATE users SET status = 'rejected'
         WHERE id = ? AND role = 'user'"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: users.php");
    exit();
}

$result = $conn->query(
    "SELECT id, fullname, username, status
     FROM users
     WHERE role = 'user'
     ORDER BY created_at DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin | Manage Users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>JAS ONLINE SHOP</h1>
    <h2>Manage User Accounts</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>Full Name</th>
            <th>Username</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($user = $result->fetch_assoc()): ?>

        <tr>
            <td><?php echo htmlspecialchars($user["fullname"]); ?></td>

            <td><?php echo htmlspecialchars($user["username"]); ?></td>

            <td><?php echo htmlspecialchars($user["status"]); ?></td>

            <td>
                <?php if ($user["status"] == "pending"): ?>

                    <form method="POST">
                        <input type="hidden" name="user_id"
                        value="<?php echo $user["id"]; ?>">

                        <button type="submit" name="approve">
                            Approve
                        </button>

                        <button type="submit" name="reject">
                            Reject
                        </button>
                    </form>

                <?php else: ?>

                    No Action

                <?php endif; ?>
            </td>
        </tr>

        <?php endwhile; ?>

    </table>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>

</div>

</body>
</html>
