<?php
session_start();
include 'includes/db.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verificar si ya existe el email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $mensaje = "⚠️ El correo ya está registrado.";
    } else {
        // Insertar nuevo usuario como cliente
        $stmt = $pdo->prepare("INSERT INTO users (email, password, role_id) VALUES (?, ?, 4)");
        if ($stmt->execute([$email, $password])) {
            // Obtener ID del nuevo usuario
            $user_id = $pdo->lastInsertId();

            // Iniciar sesión automáticamente
            $_SESSION['user_id'] = $user_id;
            $_SESSION['rol_id'] = 4;

            // Redirigir al panel del cliente
            header("Location: cliente/index.php");
            exit;
        } else {
            $mensaje = "❌ Error al registrar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente</title>
</head>
<body>

<h2>📝 Registro de Cliente</h2>

<?php if ($mensaje): ?>
    <p><strong><?= $mensaje ?></strong></p>
<?php endif; ?>

<form method="POST">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contraseña:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Registrarse</button>
</form>

<p>¿Ya tienes cuenta? <a href="index.php">Inicia sesión aquí</a></p>

</body>
</html>