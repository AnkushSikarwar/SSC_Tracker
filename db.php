<?php
session_start(); // Session start karna zaroori hai login track karne ke liye

$host = 'localhost';
$dbname = 'ssc_tracker';
$username = 'root'; // XAMPP default
$password = ''; // XAMPP default

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>