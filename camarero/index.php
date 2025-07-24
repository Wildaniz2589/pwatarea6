<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 3) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Camarero</title></head>
<body>
<h2>Bienvenido, Camarero</h2>
<ul>
    <li><a href="pedidos.php">🧾 Entregar Pedidos</a></li>
    <li><a href="../includes/logout.php">🚪 Cerrar sesión</a></li>
</ul>
</body>
</html>