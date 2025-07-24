<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Administrador</title></head>
<body>
<h2>Bienvenido, Administrador</h2>
<ul>
    <li><a href="menu.php">📋 Gestionar Menú</a></li>
    <li><a href="../includes/logout.php">🚪 Cerrar sesión</a></li>
</ul>
</body>
</html>