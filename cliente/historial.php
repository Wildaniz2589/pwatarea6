<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 4) {
    header("Location: ../index.php");
    exit;
}

include '../includes/db.php';

// Obtener pedidos del cliente actual
$stmt = $pdo->prepare("
    SELECT o.id, d.name AS plato, o.quantity, o.status, o.created_at
    FROM orders o
    JOIN dishes d ON o.dish_id = d.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Pedidos</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        table { width: 80%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
    </style>
</head>
<body>

<h2>📜 Historial de Pedidos</h2>

<?php if (count($pedidos) === 0): ?>
    <p>No has realizado ningún pedido aún.</p>
<?php else: ?>
    <table>
        <tr>
            <th>ID Pedido</th>
            <th>Plato</th>
            <th>Cantidad</th>
            <th>Estado</th>
            <th>Fecha</th>
        </tr>
        <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?= $pedido['id'] ?></td>
            <td><?= $pedido['plato'] ?></td>
            <td><?= $pedido['quantity'] ?></td>
            <td><?= ucfirst($pedido['status']) ?></td>
            <td><?= $pedido['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<br>
<a href="index.php">🏠 Volver al inicio</a> |
<a href="../includes/logout.php">🚪 Cerrar sesión</a>

</body>
</html>