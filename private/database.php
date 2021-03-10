<?php
include 'config.php';

try {
	$con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	// set the PDO error mode to exception
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo($e."\n");
	echo json_encode(array('success' => 0));
	exit;
}

// Function to check if someone is logged in
function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] != 0;
}

// Function to check if someone is an admin
function isAdmin() {
	return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1;
}
?>