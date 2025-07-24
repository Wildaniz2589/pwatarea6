<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 4) {
    header("Location: ../index.php");
    exit;
}

include '../includes/db.php';

// Obtener correo del usuario
$stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Cliente</title>
</head>
<body>

<h2>👋 Bienvenido, <?= htmlspecialchars($usuario['email']) ?>!</h2>

<ul>
    <li><a href="menu.php">📋 Ver Menú y Realizar Pedido</a></li>
    <li><a href="historial.php">🧾 Historial de Pedidos</a></li>
    <li><a href="../includes/logout.php">🚪 Cerrar Sesión</a></li>
</ul>

</body>
</html>