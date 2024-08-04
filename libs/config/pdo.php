<?php

//! -------- Localhost setting --------

$host = "localhost";
$user = "root";
$password = "root";
$dbname = "todo_app_v1";

// data source name
$dsn = "mysql:host=$host;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (Exception $e) {
    echo "exception message: " . $e->getMessage();
}