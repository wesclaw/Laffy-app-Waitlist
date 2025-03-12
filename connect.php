<?php

$config = require __DIR__ . '/config.php'; // Load database credentials

// Extract database credentials
$dbServerName = $config['DB_HOST'];
$dbUserName = $config['DB_USER'];
$dbPassword = $config['DB_PASS'];
$dbName = $config['DB_NAME'];

// Establish a secure database connection
$conn = mysqli_connect($dbServerName, $dbUserName, $dbPassword, $dbName);

// Check the connection
if (!$conn) {
  die("❌ Connection failed: " . mysqli_connect_error());
}

?>
