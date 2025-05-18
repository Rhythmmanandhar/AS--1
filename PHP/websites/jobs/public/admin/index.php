<?php
$pdo = new PDO('mysql:dbname=job;host=mysql', 'student', 'student');
session_start();

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/styles.css"/>
        <title>Jo's Jobs - Admin Home</title>
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
            <li>Jobs
                <ul>
                    <li><a href="/it.php">IT</a></li>
                    <li><a href="/hr.php">Human Resources</a></li>
                    <li><a href="/sales.php">Sales</a></li>
                </ul>
            </li>
            <li><a href="/about.html">About Us</a></li>
        </ul>
    </nav>
    <img src="/images/randombanner.php"/>
    <main class="sidebar">
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    ?>
        <section class="left">
            <ul>
                <li><a href="jobs.php">Jobs</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="archivedjobs.php">Archived Jobs</a></li>
				<li><a href="manageusers.php">Manage Users</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </section>
        <section class="right">
			<p>Welcome to Jo's Jobs, we're a recruitment agency based in Northampton. We offer a range of different office jobs. Get in touch if you'd like to list a job with us.</p>
            <h2>You are now logged in</h2>
        </section>
    <?php
    } else {
    ?>
        <h2>Log in</h2>
        <form action="index.php" method="post" style="padding: 40px">
            <label>Enter Password</label>
            <input type="password" name="password" />
            <input type="submit" name="submit" value="Log In" />
        </form>
    <?php
    }
    ?>
    </main>
    <footer>
        &copy; Jo's Jobs 2025
    </footer>
</body>
</html>