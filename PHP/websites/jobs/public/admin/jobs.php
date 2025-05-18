<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$pdo = new PDO('mysql:dbname=job;host=mysql', 'student', 'student');
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/styles.css"/>
    <title>Jo's Jobs - Job list</title>
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
        <li><a href="jobs.php">Jobs</a></li>
        <li><a href="categories.php">Categories</a></li>
        <li><a href="archivedjobs.php">Archived Jobs</a></li>
        <li><a href="manageusers.php">Manage Users</a></li>
        <li><a href="logout.php">Log Out</a></li>
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
echo '<h2>Jobs (Latest First)</h2>';
echo '<a class="new" href="addjob.php">Add new job</a>';

$selectedCategory = $_GET['category'] ?? '';
$filterTitle = $_GET['title'] ?? '';
$filterLocation = $_GET['location'] ?? '';

// Get all categories
$categoriesStmt = $pdo->query('SELECT * FROM category');
$categories = $categoriesStmt->fetchAll();

// Filter form
echo '<form method="GET" action="jobs.php">';
echo '<label for="category">Filter by category:</label>';
echo '<select name="category" id="category">';
echo '<option value="">All Categories</option>';
foreach ($categories as $category) {
    $isSelected = ($selectedCategory == $category['id']) ? 'selected' : '';
    echo '<option value="' . $category['id'] . '" ' . $isSelected . '>' . htmlspecialchars($category['name']) . '</option>';
}
echo '</select>';

echo '<label for="title">Filter by title:</label>';
echo '<input type="text" name="title" id="title" value="' . htmlspecialchars($filterTitle) . '" />';

echo '<label for="location">Filter by location:</label>';
echo '<input type="text" name="location" id="location" value="' . htmlspecialchars($filterLocation) . '" />';

echo '<input type="submit" value="Filter" />';
echo '</form>';

// Prepare job query with filters
$query = '
    SELECT job.*, category.name AS categoryName
    FROM job
    JOIN category ON job.categoryId = category.id
    WHERE job.archived = FALSE
';
$params = [];

if (!empty($selectedCategory)) {
    $query .= ' AND category.id = :categoryId';
    $params['categoryId'] = $selectedCategory;
}

if (!empty($filterTitle)) {
    $query .= ' AND job.title LIKE :title';
    $params['title'] = '%' . $filterTitle . '%';
}

if (!empty($filterLocation)) {
    $query .= ' AND job.location LIKE :location';
    $params['location'] = '%' . $filterLocation . '%';
}

$query .= ' ORDER BY job.datePosted DESC';

$stmt = $pdo->prepare($query);
$stmt->execute($params);

// Display jobs
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<th>Title</th>';
echo '<th>Category</th>';
echo '<th style="width: 15%">Salary</th>';
echo '<th>Date Received</th>';
echo '<th>Edit</th>';
echo '<th>Applicants</th>';
echo '<th>Delete</th>';
echo '<th>Archive</th>';
echo '</tr>';
echo '</thead>';

foreach ($stmt as $job) {
    $applicants = $pdo->prepare('SELECT COUNT(*) as count FROM applicants WHERE jobId = :jobId');
    $applicants->execute(['jobId' => $job['id']]);
    $applicantCount = $applicants->fetch();

    echo '<tr>';
    echo '<td>' . htmlspecialchars($job['title']) . '</td>';
    echo '<td>' . htmlspecialchars($job['categoryName']) . '</td>';
    echo '<td>' . htmlspecialchars($job['salary']) . '</td>';
    echo '<td>' . (!empty($job['datePosted']) ? htmlspecialchars($job['datePosted']) : 'N/A') . '</td>';
    echo '<td><a href="editjob.php?id=' . $job['id'] . '">Edit</a></td>';
    echo '<td><a href="applicants.php?id=' . $job['id'] . '">View applicants (' . $applicantCount['count'] . ')</a></td>';
    echo '<td>
        <form method="post" action="deletejob.php" style="margin:0;">
            <input type="hidden" name="id" value="' . $job['id'] . '" />
            <input type="submit" name="submit" value="Delete" onclick="return confirm(\'Are you sure?\');" />
        </form>
    </td>';
    echo '<td>
        <form method="post" action="archivejob.php" style="margin:0;">
            <input type="hidden" name="id" value="' . $job['id'] . '" />
            <input type="submit" value="Archive" onclick="return confirm(\'Archive this job?\');" />
        </form>
    </td>';
    echo '</tr>';
}

echo '</table>';
?>
</section>
</main>

<footer>
    &copy; Jo's Jobs 2025
</footer>
</body>
</html>