<?php
$host = 'localhost';
$dbname = 'inventariofarmaceutico';
$user = 'root';
$pass = '';

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die('Error de conexión: ' . $e->getMessage());
}
