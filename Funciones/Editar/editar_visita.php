<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//editar_visita.php

require_once __DIR__ . '/../../Conexion.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID de visita no proporcionado.");
}

// Obtener datos actuales de la visita
$sql = "SELECT * FROM visitas WHERE id_visita = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$visita = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$visita) {
    die("Visita no encontrada.");
}

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha_asignada'];
    $hora = $_POST['hora'];
    $asunto = $_POST['asunto'];

    //Actualiza la visita
    $sql = "UPDATE visitas SET fecha_asignada = ?, hora = ?, asunto = ? WHERE id_visita = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$fecha, $hora, $asunto, $id]);

    header("Location: ../Listar/listar_visitas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Visita</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
<header>
    <h1>Editar Visita</h1>
</header>
<main>
    <!--Formulario-->
    <form method="POST">
        <label>Fecha de la Visita:</label>
        <input type="date" name="fecha_asignada" value="<?= htmlspecialchars($visita['fecha_asignada']) ?>" required><br><br>

        <label>Hora:</label>
        <input type="time" name="hora" value="<?= htmlspecialchars($visita['hora']) ?>" required><br><br>

        <label>Asunto:</label>
        <input type="text" name="asunto" value="<?= htmlspecialchars($visita['asunto']) ?>" required><br><br>

        <button type="submit">Guardar Cambios</button>
    </form>
</main>
</body>
</html>
