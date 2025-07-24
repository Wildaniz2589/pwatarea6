<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: ../index.php");
    exit;
}

include '../includes/db.php';

$mensaje = '';

// Insertar nuevo plato
if (isset($_POST['nombre']) && isset($_POST['precio']) && empty($_POST['id'])) {
    $stmt = $pdo->prepare("INSERT INTO dishes (name, price) VALUES (?, ?)");
    $stmt->execute([$_POST['nombre'], $_POST['precio']]);
    $mensaje = "✅ Plato agregado correctamente.";
}

// Actualizar plato
if (isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['precio'])) {
    $stmt = $pdo->prepare("UPDATE dishes SET name = ?, price = ? WHERE id = ?");
    $stmt->execute([$_POST['nombre'], $_POST['precio'], $_POST['id']]);
    $mensaje = "✏️ Plato actualizado.";
}

// Eliminar plato
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM dishes WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $mensaje = "🗑️ Plato eliminado.";
}

// Cargar plato a editar
$editPlato = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM dishes WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editPlato = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener todos los platos
$platos = $pdo->query("SELECT * FROM dishes")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        table { width: 60%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        form { margin-top: 20px; }
        input, button { padding: 6px; margin: 4px 0; }
    </style>
</head>
<body>

<h2>Bienvenido, Administrador</h2>

<?php if ($mensaje): ?>
    <p style="color:green;"><strong><?= $mensaje ?></strong></p>
<?php endif; ?>

<h3><?= $editPlato ? 'Editar Plato' : 'Agregar Nuevo Plato' ?></h3>
<form method="POST">
    <input type="hidden" name="id" value="<?= $editPlato['id'] ?? '' ?>">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required value="<?= $editPlato['name'] ?? '' ?>"><br>
    <label>Precio:</label><br>
    <input type="number" name="precio" step="0.01" required value="<?= $editPlato['price'] ?? '' ?>"><br>
    <button type="submit"><?= $editPlato ? 'Actualizar' : 'Agregar' ?></button>
</form>

<h3>Lista de Platos</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($platos as $plato): ?>
    <tr>
        <td><?= $plato['id'] ?></td>
        <td><?= $plato['name'] ?></td>
        <td>$<?= number_format($plato['price'], 2) ?></td>
        <td>
            <a href="?edit=<?= $plato['id'] ?>">✏️ Editar</a> |
            <a href="?delete=<?= $plato['id'] ?>" onclick="return confirm('¿Eliminar este plato?')">🗑️ Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="../includes/logout.php">Cerrar sesión</a>

</body>
</html>