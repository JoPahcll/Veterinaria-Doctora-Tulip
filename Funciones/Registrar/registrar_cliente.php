<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//registrar_cliente.php

require_once __DIR__ . '/../../Conexion.php';
require_once __DIR__ . '/../../clases/Cliente.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $correo = trim($_POST['correo'] ?? '');

    if ($nombre && $apellidos && $telefono && $cedula) { // correo puede ser opcional
        $cliente = new Cliente($nombre, $apellidos, $telefono, $cedula, $correo);

        // Guardar y capturar resultado
        $exito = $cliente->guardar($pdo);

        if ($exito === true) {
            $msg = "Cliente registrado correctamente.";
        } else {
            $msg = $exito; // Podría ser mensaje de error retornado
        }
    } else {
        $msg = "Completa todos los campos obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Registrar Cliente</title>
    <link rel="stylesheet" href="../../styles.css" />
</head>
<body>
<header>
    <h1>Veterinaria Doc. Tulip</h1>
</header>

<main>
    <div class="form-registrar-container">
        <h1>Registrar Cliente</h1>

        <?php if ($msg): ?>
            <div class="error-message"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <!--Formulario-->
        <form method="POST">
            <label for="cedula">Cédula:</label>
            <input type="text" id="cedula" name="cedula" required />

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required />

            <label for="apellidos">Apellido:</label>
            <input type="text" id="apellidos" name="apellidos" required />

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" required />

            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" />

            <button type="submit">Registrar</button><!--Botón-->
        </form>

        <a href="../Listar/listar_clientes.php" class="cancelar-btn">Cancelar</a><!--Botón-->
    </div>
</main>

<footer>
    <p>Desarrollado por: José Pablo Chinchilla 12-4 Desarrollo de Software</p>
</footer>
</body>
</html>
