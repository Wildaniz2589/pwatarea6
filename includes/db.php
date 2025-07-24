<?php
$host = 'localhost';
$dbname = 'restaurant'; // Asegúrate que este sea el nombre correcto de tu base de datos
$username = 'root';     // Usuario por defecto de Laragon
$password = '';         // Normalmente sin contraseña en Laragon

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>