<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//listar_clientes.php

require_once __DIR__ . '/../../Conexion.php';
require_once __DIR__ . '/../../clases/Cliente.php';

// Obtener filtro y valor desde GET
$filtro = $_GET['filtro'] ?? '';
$valor = $_GET['valor'] ?? '';

// Buscar clientes con filtro usando método estático
$clientes = Cliente::buscarConFiltro($pdo, $filtro, $valor);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Listado de Clientes</title>
    <link rel="stylesheet" href="../../styles.css" />
    <script>
        // Confirmación antes de eliminar
        function confirmarEliminacion() {
            return confirm("¿Estás seguro de que deseas eliminar este cliente?");
        }
    </script>
</head>
<body>
<header>
    <h1>Veterinaria Doc. Tulip - Clientes</h1>
    <nav>
    <!--Menu-->
        <form action="../../index.php" method="get" style="display:inline;">
            <button type="submit">
                <svg class="icon" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
                    <path d="M12 3l8 7h-3v7h-10v-7h-3l8-7z"/>
                </svg>
                Inicio
            </button>
        </form>
        <form action="../Registrar/registrar_cliente.php" method="get" style="display:inline;">
            <button type="submit">
                <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"/>
                </svg>
                Registrar
            </button>
        </form>
        <form action="../Listar/listar_mascotas.php" method="get" style="display:inline;">
            <button type="submit">
                <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c1.3 0 2.4.84 2.82 2.02.21.6-.05 1.27-.6 1.59-.55.32-1.27.23-1.71-.22l-.51-.51-.51.51c-.44.45-1.16.54-1.71.22-.55-.32-.81-.99-.6-1.59A3.002 3.002 0 0 1 12 12zM8.5 8C9.33 8 10 8.67 10 9.5S9.33 11 8.5 11 7 10.33 7 9.5 7.67 8 8.5 8zm7 0c.83 0 1.5.67 1.5 1.5S16.33 11 15.5 11 14 10.33 14 9.5 14.67 8 15.5 8zm-6.1-3.8c.7.4.94 1.28.54 1.98s-1.28.94-1.98.54-.94-1.28-.54-1.98 1.28-.94 1.98-.54zm6.2 0c.7.4.94 1.28.54 1.98s-1.28.94-1.98.54-.94-1.28-.54-1.98 1.28-.94 1.98-.54z"/>
                </svg>
                Mascotas</button>
        </form>
        <form action="../Listar/listar_visitas.php" method="get" style="display:inline;">
            <button type="submit">
                <svg class="icon" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
                    <path d="M7 11h5v5H7z" /> <!-- Cuadrado para el día marcado -->
                    <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1 .9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM19 20H5V9h14v11z"/>
                </svg>
                Visitas Médicas
            </button>
        </form>
    </nav>

</header>

<main>
    <!--Barra de búsqueda-->
    <div class="busqueda-container">
        <form method="GET" class="form-busqueda">
            <label for="filtro">Buscar:</label>
            <select name="filtro" id="filtro">
                <option value="id_cliente" <?= $filtro === 'id_cliente' ? 'selected' : '' ?>>ID</option>
                <option value="nombre" <?= $filtro === 'nombre' ? 'selected' : '' ?>>Nombre</option>
                <option value="apellido" <?= $filtro === 'apellido' ? 'selected' : '' ?>>Apellido</option>
                <option value="cedula" <?= $filtro === 'cedula' ? 'selected' : '' ?>>Cédula</option>
            </select>

            <input type="text" name="valor" value="<?= htmlspecialchars($valor) ?>" placeholder="Buscar...">
            <button type="submit">Buscar</button>

            <a href="listar_clientes.php">
                <button type="button">Mostrar todo</button>
            </a>
        </form>
    </div>

    <!--Mensaje de eliminación de clientes-->
    <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'eliminado'): ?>
        <p style="color: green;">Cliente eliminado correctamente.</p>
    <?php endif; ?>

    <!--Tabla-->
    <table border="1">
        <thead>
            <tr>
                <th>ID Cliente</th>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($clientes) === 0): ?>
                <tr><td colspan="7" style="text-align:center;">No se encontraron clientes.</td></tr>
            <?php else: ?>
                <?php foreach ($clientes as $c): ?>

                    <!--Oculta la información del cliente falso para la opción "Sin propietario"-->
                    <?php if ($c->getId() == 1) continue; ?> 
                    <tr>
                        <td><?= htmlspecialchars($c->getId()) ?></td>
                        <td><?= htmlspecialchars($c->getCedula()) ?></td>
                        <td><?= htmlspecialchars($c->getNombre()) ?></td>
                        <td><?= htmlspecialchars($c->getApellido()) ?></td>
                        <td><?= htmlspecialchars($c->getTelefono()) ?></td>
                        <td><?= htmlspecialchars($c->getCorreo()) ?></td>

                        <!--Botones-->
                        <td style="display: flex; gap: 5px;">
                            <form action="../Editar/editar_cliente.php" method="get" style="margin:0;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($c->getId()) ?>">
                                <button type="submit">Editar</button>
                            </form>

                            <form action="../Eliminar/eliminar_cliente.php" method="post" style="margin:0;" onsubmit="return confirmarEliminacion();">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($c->getId()) ?>">
                                <button type="submit" style="background-color:red; color:white;">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<footer>
    <p>Desarrollado por: José Pablo Chinchilla 12-4 Desarrollo de Software</p>
</footer>
</body>
</html>
