<?php
global $pdo;
$pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "root");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
