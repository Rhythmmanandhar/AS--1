<?php
session_start();
$pdo = new PDO('mysql:dbname=job;host=mysql', 'student', 'student');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id']; // Store user id for client job ownership
        header('Location: jobs.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/styles.css"/>
    <title>Log In</title>
</head>
<body>
<header>
    <section>
        <aside>
            <h3>Office Hours:</h3>
            <p>Mon-Fri: 09:00-17:30</p>
            <p>Sat: 09:00-17:00</p>
            <p>Sun: Closed</p>
        </aside>
        <h1>Jo's Jobs</h1>
    </section>
</header>

<nav>
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/about.html">About Us</a></li>
        <li><a href="/careers.php">Careers Advice</a></li>
    </ul>
</nav>

<img src="/images/randombanner.php"/>

<main class="sidebar">
<section class="right">
    <h2>Log in</h2>
    <?php if (isset($error)) echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>'; ?>
    <form action="login.php" method="post">
        <label>Username</label>
        <input type="text" name="username" required />
        <label>Password</label>
        <input type="password" name="password" required />
        <input type="submit" value="Log In" />
    </form>
</section>
</main>

<footer>
    &copy; Jo's Jobs 2025
</footer>
</body>
</html>