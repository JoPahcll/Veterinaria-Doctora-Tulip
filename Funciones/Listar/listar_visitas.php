<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//listar_visitas.php

require_once __DIR__ . '/../../Conexion.php';
require_once __DIR__ . '/../../clases/Visita.php';

$filtro = $_GET['filtro'] ?? '';
$valor = $_GET['valor'] ?? '';

$visitas = Visita::buscarConFiltro($pdo, $filtro, $valor);//Metodo de buscar con filtro

$hoy = date('Y-m-d');

//Actualiza el estado usando la fecha
$sqlUpdateCaducadas = "
    UPDATE visitas
    SET estado = 'Caducada'
    WHERE estado = 'Vigente' AND fecha_asignada < :hoy
";

$stmt = $pdo->prepare($sqlUpdateCaducadas);
$stmt->execute(['hoy' => $hoy]);


$sqlUpdateCaducadas = "
    UPDATE visitas v
    LEFT JOIN diagnostico d ON v.id_visita = d.id_visita
    SET v.estado = 'Caducada'
    WHERE v.estado = 'Vigente' 
      AND v.fecha_asignada < CURDATE() 
      AND d.id_visita IS NULL
";
$stmt = $pdo->prepare($sqlUpdateCaducadas);
$stmt->execute();

// Contar visitas por estado
$contadorEstados = [
    'Vigente' => 0,
    'Concluida' => 0,
    'Caducada' => 0,
];

//Obtiene el estado
foreach ($visitas as $visita) {
    $estado = $visita->getEstado();
    if (isset($contadorEstados[$estado])) {
        $contadorEstados[$estado]++;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Listado de Visitas</title>
        <link rel="stylesheet" href="../../styles.css">
    </head>
    <body>
        <header>
            <h1>Veterinaria Doc. Tulip - Citas</h1>
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
                <form action="../Registrar/registrar_visita.php" method="get" style="display:inline;">
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
                <form action="listar_clientes.php" method="get" style="display:inline;">
                    <button type="submit">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 
                            1.79-4 4 1.79 4 4 4zM6 18v-2c0-2.21 3-3 
                            6-3s6 .79 6 3v2H6z"/>
                        </svg>
                        Clientes
                    </button>
                </form>
                <form action="../../Diagnostico/diagnostico.php" method="get" style="display:inline;">
                    <button type="submit">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
                            <path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1 .9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
                        </svg>
                        Diagnóstico
                    </button>
                </form>
            </nav>
        </header>

        <main>
            <!--Barra de búsqueda-->
            <div class="busqueda-container">
                <form class="form-busqueda" method="GET" style="margin-bottom: 20px;">
                    <label for="filtro">Buscar por:</label>
                    <select name="filtro" id="filtro" onchange="toggleValorInput()">
                        <option value="id_visita" <?= $filtro == 'id_visita' ? 'selected' : '' ?>>ID Visita</option>
                        <option value="fecha" <?= $filtro == 'fecha' ? 'selected' : '' ?>>Fecha</option>
                        <option value="asunto" <?= $filtro == 'asunto' ? 'selected' : '' ?>>Asunto</option>
                        <option value="nombre_mascota" <?= $filtro == 'nombre_mascota' ? 'selected' : '' ?>>Nombre Mascota</option>
                        <option value="estado" <?= $filtro == 'estado' ? 'selected' : '' ?>>Estado</option>
                    </select>

                    <div id="valor-texto-container" style="<?= $filtro == 'estado' ? 'display:none;' : 'display:block;' ?>">
                        <input type="text" name="valor" id="valor-texto" value="<?= htmlspecialchars($valor) ?>" placeholder="Buscar...">
                    </div>

                    <div id="valor-estado-container" style="<?= $filtro == 'estado' ? 'display:block;' : 'display:none;' ?>">
                        <select name="valor" id="valor-estado">
                            <option value="">--Seleccione estado--</option>
                            <option value="Vigente" <?= $valor == 'Vigente' ? 'selected' : '' ?>>Vigente</option>
                            <option value="Concluida" <?= $valor == 'Concluida' ? 'selected' : '' ?>>Concluida</option>
                            <option value="Caducada" <?= $valor == 'Caducada' ? 'selected' : '' ?>>Caducada</option>
                        </select>
                    </div>

                    <script>//Script para filtrar por estado o otros filtros
                        function toggleValorInput() {
                            const filtro = document.getElementById('filtro').value;

                            const textoContainer = document.getElementById('valor-texto-container');
                            const estadoContainer = document.getElementById('valor-estado-container');

                            const inputTexto = document.getElementById('valor-texto');
                            const selectEstado = document.getElementById('valor-estado');

                            //Metodo de filtro
                            if (filtro === 'estado') {
                                textoContainer.style.display = 'none';
                                estadoContainer.style.display = 'block';

                                inputTexto.disabled = true;
                                selectEstado.disabled = false;
                            } else {
                                textoContainer.style.display = 'block';
                                estadoContainer.style.display = 'none';

                                inputTexto.disabled = false;
                                selectEstado.disabled = true;
                            }
                        }
                        window.onload = toggleValorInput;
                    </script>

                    <button type="submit">Buscar</button>

                    <a href="listar_visitas.php">
                        <button type="button">Mostrar todo</button>
                    </a>
                </form>
            </div>

            <!--Tabla-->
            <table border="1">
                <thead>
                    <tr>
                        <!--Muestra la cantidad de visitas en relación con su estado-->
                        <th colspan="9" style="background-color: #eef6ff; color: #017efcff; font-weight: bold; padding: 10px; text-align: center;">
                            Visitas: 
                            <span style="color: green;">Vigentes <?= $contadorEstados['Vigente'] ?></span> &nbsp;&nbsp;|&nbsp;&nbsp; 
                            <span style="color: blue;">Concluidas <?= $contadorEstados['Concluida'] ?></span> &nbsp;&nbsp;|&nbsp;&nbsp; 
                            <span style="color: red;">Caducadas <?= $contadorEstados['Caducada'] ?></span>
                        </th>
                    </tr>
                    <tr>
                        <th>ID Visita</th>
                        <th>Fecha Registro</th>
                        <th>Fecha Visita</th>
                        <th>Hora</th>
                        <th>Asunto</th>
                        <th>ID Mascota</th>
                        <th>Nombre Mascota</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($visitas) > 0): ?>
                    <?php foreach ($visitas as $v): ?>
                            <tr><!--Obtiene los datos y los inserta-->
                                <td><?= htmlspecialchars($v->getIdVisita()) ?></td>
                                <td><?= htmlspecialchars($v->getFechaAsignacion()) ?></td>
                                <td><?= htmlspecialchars($v->getFechaAsignada()) ?></td>
                                <td><?= htmlspecialchars($v->getHora()) ?></td>
                                <td><?= htmlspecialchars($v->getAsunto()) ?></td>
                                <td><?= htmlspecialchars($v->getIdMascota()) ?></td>
                                <td><?= htmlspecialchars($v->getNombreMascota()) ?></td>
                                <td><?= htmlspecialchars($v->getEstado()) ?></td>

                                <!--Botones-->
                                <td style="display: flex; gap: 5px;">
                                    <form action="../Editar/editar_visita.php" method="get" style="margin:0;">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($v->getIdVisita()) ?>">
                                        <button type="submit">Editar</button>
                                    </form>

                                    <form action="../Eliminar/eliminar_visita.php" method="post" style="margin:0;" onsubmit="return confirm('¿Seguro que deseas eliminar esta visita?');">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($v->getIdVisita()) ?>">
                                        <button type="submit" style="background-color:red; color:white;">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No se encontraron visitas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>

        <footer>
            <p>Desarrollado por: José Pablo Chinchilla 12-4 Desarrollo de Software</p>
        </footer>
    </body>
</html>