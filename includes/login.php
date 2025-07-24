<?php
session_start();

// Incluye la conexión a la base de datos
include __DIR__ . '/db.php';

// Verificación rápida de conexión
if (!$pdo) {
    die("Fallo conexión");
} else {
    echo "Conexión OK<br>";
}

// Mostrar errores (útil para desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verifica si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Consulta para buscar el usuario
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && $usuario['password'] == $password) {
        // Iniciar sesión
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['rol_id'] = $usuario['role_id'];

        // Redireccionar según el rol
        switch ($usuario['role_id']) {
            case 1: // Administrador
                header("Location: ../admin/menu.php");
                break;
            case 2: // Chef
                header("Location: ../chef/pedidos.php");
                break;
            case 3: // Camarero
                header("Location: ../camarero/pedidos.php");
                break;
            case 4: // Cliente
                header("Location: ../cliente/menu.php");
                break;
            default:
                header("Location: ../index.php");
                break;
        }
        exit;
    } else {
        // Credenciales incorrectas
        $mensaje = "Credenciales incorrectas.";
        header("Location: ../index.php?error=" . urlencode($mensaje));
        exit;
    }
} else {
    // Acceso no válido
    header("Location: ../index.php");
    exit;
}