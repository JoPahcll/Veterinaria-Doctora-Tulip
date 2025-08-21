<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//listar_mascotas.php

require_once __DIR__ . '/../../Conexion.php';
require_once __DIR__ . '/../../clases/Mascota.php';

$filtro = $_GET['filtro'] ?? '';
$valor = $_GET['valor'] ?? '';

if (!empty($filtro) && !empty($valor)) {
    $mascotas = Mascota::obtenerConDueños($pdo, $filtro, $valor);
} else {
    // Si no hay filtro o valor, mostrar todo
    $mascotas = Mascota::obtenerConDueños($pdo);
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Listado de Mascotas</title>
        <link rel="stylesheet" href="../../styles.css">
    </head>
    
    <body>
        <header>
            <h1>Veterinaria Doc Tulip - Mascotas y Dueños</h1>

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
                <form action="../../Funciones/Registrar/registrar_mascota.php" method="get" style="display:inline;">
                    <button type="submit">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"/>
                        </svg>
                        Registrar
                    </button>
                </form>
                <form action="../../Funciones/Listar/listar_clientes.php" method="get" style="display:inline;">
                    <button type="submit">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 
                            1.79-4 4 1.79 4 4 4zM6 18v-2c0-2.21 3-3 
                            6-3s6 .79 6 3v2H6z"/>
                        </svg>
                        Clientes
                    </button>
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
                        <option value="id_mascota" <?= $filtro === 'id_mascota' ? 'selected' : '' ?>>ID</option>
                        <option value="nombre_mascota" <?= $filtro === 'nombre_mascota' ? 'selected' : '' ?>>Nombre de la mascota</option>
                        <option value="especie" <?= $filtro === 'especie' ? 'selected' : '' ?>>Especie</option>
                        <option value="raza" <?= $filtro === 'raza' ? 'selected' : '' ?>>Raza</option>
                        <option value="dueños" <?= $filtro === 'dueños' ? 'selected' : '' ?>>Nombre del dueño</option>
                    </select>

                    <input type="text" name="valor" value="<?= htmlspecialchars($valor) ?>" placeholder="Buscar...">
                    <button type="submit">Buscar</button>

                    <a href="listar_mascotas.php">
                        <button type="button">Mostrar todo</button>
                    </a>
                </form>
            </div>
            
            <!--Tabla-->
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Mascota</th>
                        <th>Especie</th>
                        <th>Raza</th>
                        <th>Edad</th>
                        <th>Sexo</th>
                        <th>Dueños</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mascotas)): ?>
                        <tr>
                            <td colspan="8" style="text-align:center;">No se encontraron mascotas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mascotas as $m): ?>
                            <tr><!--Obtiene los datos y los inserta-->
                                <td><?= htmlspecialchars($m['id_mascota']) ?></td>
                                <td><?= htmlspecialchars($m['nombre']) ?></td>
                                <td><?= htmlspecialchars($m['especie']) ?></td>
                                <td><?= htmlspecialchars($m['raza']) ?></td>
                                <td><?= htmlspecialchars($m['edad']) ?></td>
                                <td><?= htmlspecialchars($m['sexo']) ?></td>
                                <td>
                                    <?php if (!empty($m['dueños'])): ?>
                                        <?php foreach ($m['dueños'] as $dueño): ?>
                                            <a href="../Listar/listar_clientes.php?filtro=id_cliente&valor=<?= urlencode($dueño['id_cliente']) ?>" 
                                            title="Ver datos del cliente <?= htmlspecialchars($dueño['nombre_completo']) ?>">
                                                <?= htmlspecialchars($dueño['nombre_completo']) ?>
                                            </a><br>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <em>Sin dueño asignado</em>
                                    <?php endif; ?>
                                </td>

                                <!--Botones-->
                                <td style="display: flex; gap: 5px;">
                                    <form action="../Editar/editar_mascota.php" method="get" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($m['id_mascota']) ?>">
                                        <button type="submit">Editar</button>
                                    </form>

                                    <form action="../../Historial/historial.php" method="get" style="display:inline;">
                                        <input type="hidden" name="id_mascota" value="<?= htmlspecialchars($m['id_mascota']) ?>">
                                        <button type="submit" class="btn btn-info">Historial</button>
                                    </form>

                                    <form action="../Eliminar/eliminar_mascota.php" method="post" style="display:inline;" onsubmit="return confirmarEliminacion();">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($m['id_mascota']) ?>">
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

        <script>
            function confirmarEliminacion() {
                return confirm('¿Seguro que deseas eliminar esta mascota? Esta acción no se puede deshacer.');
            }
        </script>
    </body>
</html>