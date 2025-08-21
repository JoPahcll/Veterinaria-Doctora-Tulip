<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//registrar_visita.php

require_once __DIR__ . '/../../Conexion.php';
require_once __DIR__ . '/../../clases/Visita.php';

//Obtiene los datos desde mascota
$mascotas = $pdo->query("
    SELECT m.id_mascota, m.nombre,
        (SELECT CONCAT(c.nombre, ' ', c.apellido)
         FROM mascotas_clientes mc2
         JOIN clientes c ON mc2.id_cliente = c.id_cliente
         WHERE mc2.id_mascota = m.id_mascota
         LIMIT 1) AS nombre_cliente
    FROM mascotas m
    ORDER BY m.nombre
")->fetchAll(PDO::FETCH_ASSOC);

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_asignada = $_POST['fecha_asignada'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $asunto = $_POST['asunto'] ?? '';
    $id_mascota = $_POST['id_mascota'] ?? '';

    if ($fecha_asignada && $hora && $asunto && $id_mascota) {//Determina el resultado del registro
        try {
            $visita = new Visita(//Crea la nueva visita
                null,
                $fecha_asignada,
                $hora,
                $asunto,
                (int)$id_mascota,
                null,
                null
            );
            if ($visita->guardar($pdo)) {
                header("Location: ../Listar/listar_visitas.php");
                exit;
            } else {
                $error = "Error al registrar la visita.";
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, complete todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Registrar Visita</title>
    <link rel="stylesheet" href="../../styles.css" />
</head>
<body>
<header>
    <h1>Veterinaria Doc. Tulip</h1>
</header>

<main>
    <div class="form-registrar-container">
        <h1>Registrar Visita</h1>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!--Formulario-->
        <form method="POST" novalidate>
            <label for="fecha_asignada">Fecha:</label>
            <input type="date" id="fecha_asignada" name="fecha_asignada" required
                value="<?= isset($_POST['fecha_asignada']) ? htmlspecialchars($_POST['fecha_asignada']) : '' ?>" />

            <label for="hora">Hora:</label>
            <input type="time" id="hora" name="hora" required
                value="<?= isset($_POST['hora']) ? htmlspecialchars($_POST['hora']) : '' ?>" />

            <label for="asunto">Asunto:</label>
            <input type="text" id="asunto" name="asunto" required
                value="<?= isset($_POST['asunto']) ? htmlspecialchars($_POST['asunto']) : '' ?>" />

            <label for="id_mascota">Mascota (dueño):</label>
            <select id="id_mascota" name="id_mascota" required>
                <option value="">Seleccione una mascota</option>
                <?php foreach ($mascotas as $m): ?>
                    <option value="<?= (int)$m['id_mascota'] ?>"
                        <?= (isset($_POST['id_mascota']) && $_POST['id_mascota'] == $m['id_mascota']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['nombre'] . ' (' . $m['nombre_cliente'] . ')') ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Registrar</button>
        </form>

        <a href="../Listar/listar_visitas.php" class="cancelar-btn">Cancelar</a>
    </div>
</main>

<footer>
    <p>Desarrollado por: José Pablo Chinchilla 12-4 Desarrollo de Software</p>
</footer>
</body>
</html>
