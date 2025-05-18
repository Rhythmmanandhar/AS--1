<?php
$pdo = new PDO('mysql:dbname=job;host=mysql', 'student', 'student');
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/styles.css"/>
    <title>Jo's Jobs - Archived Jobs</title>
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
    <section class="left">
        <ul>
            <li><a href="jobs.php">Jobs</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="archivedjobs.php">Archived Jobs</a></li>
        </ul>
    </section>

    <section class="right">
        <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo '<h2>Archived Jobs</h2>';

            $stmt = $pdo->query('SELECT * FROM job WHERE archived = TRUE');
            $jobs = $stmt->fetchAll();

            if (empty($jobs)) {
                echo '<p>No archived jobs found.</p>';
            } else {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Title</th>';
                echo '<th style="width: 15%">Salary</th>';
                echo '<th style="width: 15%">Actions</th>';
                echo '</tr>';
                echo '</thead>';
                foreach ($jobs as $job) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($job['title']) . '</td>';
                    echo '<td>' . htmlspecialchars($job['salary']) . '</td>';
                    echo '<td>
                        <form method="post" action="restorejob.php" style="margin: 0;">
                            <input type="hidden" name="id" value="' . htmlspecialchars($job['id']) . '" />
                            <input type="submit" value="Restore" />
                        </form>
                    </td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
        } else {
            ?>
            <h2>Log in</h2>
            <form action="index.php" method="post">
                <label>Password</label>
                <input type="password" name="password" />
                <input type="submit" name="submit" value="Log In" />
            </form>
            <?php
        }
        ?>
    </section>
</main>

<footer>
    &copy; Jo's Jobs 2025
</footer>
</body>
</html>
