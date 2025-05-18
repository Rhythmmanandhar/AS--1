<?php
$pdo = new PDO('mysql:dbname=job;host=mysql', 'student', 'student');
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
	if (isset($_POST['id'])) {
		// Archive the job instead of deleting it
		$stmt = $pdo->prepare('UPDATE job SET archived = TRUE WHERE id = :id');
		$stmt->execute(['id' => $_POST['id']]);
	}
	
	header('Location: jobs.php');
}
?>
