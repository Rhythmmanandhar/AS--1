<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Restrict access for client users
if (isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
    echo "<p style='color:red;'>Access denied. Clients cannot manage users.</p>";
    exit;
}
$pdo = new PDO('mysql:dbname=job;host=mysql', 'student', 'student');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        // Prevent adding a user with username 'mainadmin'
        if (strtolower($username) === 'mainadmin') {
            $add_error = "You cannot create another main admin user.";
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (:username, :password, :role)');
            $stmt->execute(['username' => $username, 'password' => $password, 'role' => $role]);
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        // Get the username for this id
        $stmt = $pdo->prepare('SELECT username, role FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        // Prevent deleting the main admin user
        if ($user && strtolower($user['username']) === 'mainadmin') {
            $delete_error = "You cannot delete the main admin user.";
        } else {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
        }
    }
}

$users = $pdo->query('SELECT * FROM users')->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/styles.css"/>
    <title>Manage Users</title>
</head>
<body>
<header>
    <section>
        <h1>Jo's Jobs</h1>
    </section>
</header>

<nav>
    <ul>
        <li><a href="jobs.php">Jobs</a></li>
        <li><a href="categories.php">Categories</a></li>
        <li><a href="archivedjobs.php">Archived Jobs</a></li>
        <li><a href="manageusers.php">Manage Users</a></li>
        <li><a href="logout.php">Log Out</a></li>
    </ul>
</nav>

<img src="/images/randombanner.php"/>

<main class="sidebar">
<section class="right">
    <h2>Manage Users</h2>
    <?php if (isset($add_error)) echo '<p style="color:red;">' . htmlspecialchars($add_error) . '</p>'; ?>
    <form method="post">
        <label>Username</label>
        <input type="text" name="username" required />
        <label>Password</label>
        <input type="password" name="password" required />
        <label>Role</label>
        <select name="role" required>
            <option value="staff">Staff</option>
            <option value="admin">Admin</option>
            <option value="client">Client</option>
        </select>
        <input type="submit" name="add" value="Add User" />
    </form>

    <h3>Existing Users</h3>
    <?php if (isset($delete_error)) echo '<p style="color:red;">' . htmlspecialchars($delete_error) . '</p>'; ?>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <?php if (strtolower($user['username']) !== 'mainadmin'): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>" />
                            <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure?');" />
                        </form>
                        <?php else: ?>
                            <span style="color: #888;">Main admin cannot be deleted</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
</main>

<footer>
    &copy; Jo's Jobs 2025
</footer>
</body>
</html>