<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: ../index.php");
    exit;
}

include '../includes/db.php';
$mensaje = '';

// Actualizar estado del pedido
if (isset($_POST['pedido_id']) && isset($_POST['nuevo_estado'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if ($stmt->execute([$_POST['nuevo_estado'], $_POST['pedido_id']])) {
        $mensaje = "✅ Pedido actualizado.";
    } else {
        $mensaje = "❌ Error al actualizar.";
    }
}

// Obtener todos los pedidos
$stmt = $pdo->query("
    SELECT o.id, d.name AS plato, o.quantity, o.status, o.created_at, u.email AS cliente
    FROM orders o
    JOIN dishes d ON o.dish_id = d.id
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos - Chef</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        table { width: 90%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    </style>
</head>
<body>

<h2>Panel del Chef – Gestión de Pedidos</h2>

<?php if ($mensaje): ?>
    <p style="color:green;"><strong><?= $mensaje ?></strong></p>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Plato</th>
        <th>Cantidad</th>
        <th>Cliente</th>
        <th>Estado</th>
        <th>Fecha</th>
        <th>Actualizar</th>
    </tr>
    <?php foreach ($pedidos as $pedido): ?>
    <tr>
        <td><?= $pedido['id'] ?></td>
        <td><?= $pedido['plato'] ?></td>
        <td><?= $pedido['quantity'] ?></td>
        <td><?= $pedido['cliente'] ?></td>
        <td><?= ucfirst($pedido['status']) ?></td>
        <td><?= $pedido['created_at'] ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                <select name="nuevo_estado">
                    <option value="en preparación" <?= $pedido['status'] == 'en preparación' ? 'selected' : '' ?>>En preparación</option>
                    <option value="listo" <?= $pedido['status'] == 'listo' ? 'selected' : '' ?>>Listo</option>
                </select>
                <button type="submit">Actualizar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="../includes/logout.php">Cerrar sesión</a>

</body>
</html>