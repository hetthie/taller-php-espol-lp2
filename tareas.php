<?php
session_start();
require "tarea.php";

if (!isset($_SESSION['cedula'])) {
    header("Location: ingreso.php");
    exit;
}

$usuario = $_SESSION['cedula'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar']) && trim($_POST['texto']) !== "") {
        guardarTarea($usuario, trim($_POST['texto']));
    } elseif (isset($_POST['completar'])) {
        completarTarea($usuario, $_POST['id']);
    } elseif (isset($_POST['eliminar'])) {
        eliminarTarea($usuario, $_POST['id']);
    }
   
    header("Location: tareas.php");
    exit;
}

$tareas = listarTareas($usuario);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Tareas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="contenedor">
    <h1>Mis Tareas</h1>
    <p>
        Usuario: <?= htmlspecialchars($_SESSION['usuario'] ?? $usuario) ?>
        — <a href="logout.php">Cerrar sesión</a>
    </p>

    <form method="POST" action="tareas.php">
        <label>Nueva tarea:</label>
        <input type="text" name="texto" maxlength="100" required>
        <input type="submit" name="agregar" value="Agregar">
    </form>

    <h2>Pendientes</h2>
    <?php if (empty($tareas['pendientes'])): ?>
        <p>No tienes tareas pendientes.</p>
    <?php else: ?>
        <table>
            <tr><th>Tarea</th><th>Acciones</th></tr>
            <?php foreach ($tareas['pendientes'] as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['texto']) ?></td>
                <td>
                    <form method="POST" action="tareas.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($t['id']) ?>">
                        <input type="submit" name="completar" value="Completar">
                    </form>
                    <form method="POST" action="tareas.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($t['id']) ?>">
                        <input type="submit" name="eliminar" value="Eliminar">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <h2>Completadas</h2>
    <?php if (empty($tareas['completadas'])): ?>
        <p>No tienes tareas completadas.</p>
    <?php else: ?>
        <table>
            <tr><th>Tarea</th><th>Acciones</th></tr>
            <?php foreach ($tareas['completadas'] as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['texto']) ?></td>
                <td>
                    <form method="POST" action="tareas.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($t['id']) ?>">
                        <input type="submit" name="eliminar" value="Eliminar">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
