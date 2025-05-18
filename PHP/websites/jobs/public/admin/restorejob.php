<?php
$pdo = new PDO('mysql:dbname=job;host=mysql', 'student', 'student');

if (isset($_POST['id'])) {
    $stmt = $pdo->prepare('UPDATE job SET archived = FALSE WHERE id = :id');
    $stmt->execute(['id' => $_POST['id']]);
}

header('Location: archivedjobs.php');
?>
