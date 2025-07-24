

<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

echo "<h1>Bienvenido, " . $_SESSION['username'] . "!</h1>";
echo "<p>Rol: " . $_SESSION['role_id'] . "</p>";
echo '<a href="../includes/logout.php">Cerrar sesión</a>';
?>