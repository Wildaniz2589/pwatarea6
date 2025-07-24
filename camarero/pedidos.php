<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 3) {
    header("Location: ../index.php");
    exit;
}

include '../includes/db.php';
$mensaje = '';

// Marcar pedido como entregado
if (isset($_POST['pedido_id']) && isset($_POST['entregar'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = 'entregado' WHERE id = ?");
    if ($stmt->execute([$_POST['pedido_id']])) {
        $mensaje = "🚚 Pedido entregado.";
    } else {
        $mensaje = "❌ Error al entregar.";
    }
}

// Obtener pedidos listos y entregados
$stmt = $pdo->query("
    SELECT o.id, d.name AS plato, o.quantity, o.status, o.created_at, u.email AS cliente
    FROM orders o
    JOIN dishes d ON o.dish_id = d.id
    JOIN users u ON o.user_id = u.id
    WHERE o.status IN ('listo', 'entregado')
    ORDER BY o.created_at DESC
");
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos – Camarero</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        table { width: 90%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    </style>
</head>
<body>

<h2>Panel del Camarero – Entregas</h2>

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
        <th>Acción</th>
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
            <?php if ($pedido['status'] === 'listo'): ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                    <button type="submit" name="entregar">Marcar como entregado</button>
                </form>
            <?php else: ?>
                ✅ Entregado
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="../includes/logout.php">Cerrar sesión</a>

</body>
</html>