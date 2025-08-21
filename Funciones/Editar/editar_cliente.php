<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//editar_cliente.php

require_once __DIR__ . '/../../Conexion.php';
require_once __DIR__ . '/../../clases/Cliente.php';

if (!isset($_GET['id'])) {//Busca el id del cliente
    echo "ID de cliente no proporcionado.";
    exit;
}

$id_cliente = $_GET['id'];

// Obtener objeto Cliente por ID usando la clase
$cliente = Cliente::obtenerPorId($pdo, $id_cliente);

if (!$cliente) {
    echo "Cliente no encontrado.";
    exit;
}

// Si se envió el formulario, procesar la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente->setNombre(trim($_POST['nombre']));
    $cliente->setApellido(trim($_POST['apellido']));
    $cliente->setTelefono(trim($_POST['telefono']));
    $cliente->setCedula(trim($_POST['cedula']));
    $cliente->setCorreo(trim($_POST['correo']));

    try {
        $exito = $cliente->actualizar($pdo);
        if ($exito) {
            header("Location: ../Listar/listar_clientes.php");
            exit;
        } else {
            $error = "Error al actualizar el cliente.";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Editar Cliente</title>
        <link rel="stylesheet" href="../../styles.css">
        <style>
            .clientes-lista {
                border: 1px solid #ccc;
                padding: 8px;
                max-height: 200px;
                overflow-y: auto;
                width: 280px;
                border-radius: 5px;
                background: #f9f9f9;
                margin-bottom: 15px;
            }
            .cliente-item {
                padding: 8px;
                cursor: pointer;
                border-radius: 4px;
                margin-bottom: 4px;
                user-select: none;
                transition: background-color 0.2s;
            }
            .cliente-item:hover {
                background-color: #ddd;
            }
            .cliente-item.selected {
                background-color: #007bff;
                color: white;
                font-weight: bold;
            }
            .error-message {
                color: red;
                margin-bottom: 15px;
            }
        </style>

    </head>

    <body>
        <header><h1>Editar Cliente</h1></header>

        <!--Mensaje de error-->
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!--Formulario-->
        <form method="POST">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($cliente->getNombre()) ?>" required><br>

            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?= htmlspecialchars($cliente->getApellido()) ?>" required><br>

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($cliente->getTelefono()) ?>" required><br>

            <label>Cédula:</label>
            <input type="text" name="cedula" value="<?= htmlspecialchars($cliente->getCedula()) ?>" required><br>

            <label>Correo:</label>
            <input type="email" name="correo" value="<?= htmlspecialchars($cliente->getCorreo()) ?>"><br><br>

            <button type="submit">Guardar Cambios</button>
            <a href="../Listar/listar_clientes.php"><button type="button">Cancelar</button></a>
        </form>
    </body>
</html>
