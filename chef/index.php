<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Chef</title></head>
<body>
<h2>Bienvenido, Chef</h2>
<ul>
    <li><a href="pedidos.php">🍳 Ver/Actualizar Pedidos</a></li>
    <li><a href="../includes/logout.php">🚪 Cerrar sesión</a></li>
</ul>
</body>
</html>