<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 4) {
    header("Location: ../index.php");
    exit;
}

include '../includes/db.php';
$mensaje = '';

// Insertar nuevo pedido
if (isset($_POST['dish_id']) && isset($_POST['cantidad'])) {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, dish_id, quantity, status, created_at) VALUES (?, ?, ?, 'pendiente', NOW())");
    if ($stmt->execute([$_SESSION['user_id'], $_POST['dish_id'], $_POST['cantidad']])) {
        $mensaje = "✅ Pedido realizado correctamente.";
    } else {
        $mensaje = "❌ Error al realizar el pedido.";
    }
}

// Obtener todos los platos del menú
$platos = $pdo->query("SELECT * FROM dishes")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú del Restaurante</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        table { width: 80%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
    </style>
</head>
<body>

<h2>📖 Menú del Restaurante</h2>

<?php if ($mensaje): ?>
    <p style="color:green;"><strong><?= $mensaje ?></strong></p>
<?php endif; ?>

<form method="POST">
    <label for="dish_id">Elige un plato:</label>
    <select name="dish_id" required>
        <?php foreach ($platos as $plato): ?>
            <option value="<?= $plato['id'] ?>">
                <?= $plato['name'] ?> - $<?= $plato['price'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="cantidad">Cantidad:</label>
    <input type="number" name="cantidad" value="1" min="1" required>

    <button type="submit">Pedir</button>
</form>

<h3>📝 Menú disponible</h3>
<table>
    <tr>
        <th>Plato</th>
        <th>Precio</th>
    </tr>
    <?php foreach ($platos as $plato): ?>
    <tr>
        <td><?= $plato['name'] ?></td>
        <td>$<?= $plato['price'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="index.php">🏠 Volver al inicio</a> |
<a href="historial.php">📜 Ver Historial</a> |
<a href="../includes/logout.php">🚪 Cerrar sesión</a>

</body>
</html>