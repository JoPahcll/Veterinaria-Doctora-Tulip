<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//historial.php

require_once __DIR__ . '/../Conexion.php';

if (!isset($_GET['id_mascota']) || empty($_GET['id_mascota'])) {
    die("ID de mascota no especificado.");
}

$id_mascota = $_GET['id_mascota'];

// Obtener nombre de mascota y cliente principal (el primero que encuentre)
$sql_info = "
    SELECT m.nombre AS nombre_mascota, 
           c.nombre AS nombre_cliente, c.apellido AS apellido_cliente
    FROM mascotas m
    JOIN mascotas_clientes mc ON m.id_mascota = mc.id_mascota
    JOIN clientes c ON mc.id_cliente = c.id_cliente
    WHERE m.id_mascota = ?
    LIMIT 1
";
$stmt_info = $pdo->prepare($sql_info);
$stmt_info->execute([$id_mascota]);
$info = $stmt_info->fetch(PDO::FETCH_ASSOC);

if (!$info) {
    die("Mascota o cliente no encontrado.");
}

// Consulta para mostrar diagnósticos sin repetidos, concatenando dueños
$sql = "
    SELECT 
        d.id_diagnostico, d.id_visita, v.fecha_asignada AS fecha,
        GROUP_CONCAT(CONCAT(c.nombre, ' ', c.apellido) SEPARATOR ', ') AS clientes,
        d.costo_total AS costo
    FROM diagnostico d
    JOIN visitas v ON d.id_visita = v.id_visita
    JOIN mascotas m ON v.id_mascota = m.id_mascota
    JOIN mascotas_clientes mc ON m.id_mascota = mc.id_mascota
    JOIN clientes c ON mc.id_cliente = c.id_cliente
    WHERE m.id_mascota = ?
    GROUP BY d.id_diagnostico, d.id_visita, v.fecha_asignada, d.costo_total
    ORDER BY v.fecha_asignada DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_mascota]);
$diagnosticos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <title>Historial de Mascota</title>
        <style> /*Diseño*/
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: #f9f9f9;
                margin: 20px;
                color: #333;
            }
            h2 {
                margin-bottom: 20px;
                color: #000000ff;
                border-bottom: 2px solid #145a32;
                padding-bottom: 5px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                background: white;
                box-shadow: 0 0 8px rgba(0,0,0,0.1);
                border-radius: 6px;
                overflow: hidden;
            }
            thead {
                background-color: #1E8449;
                color: white;
            }
            thead th {
                padding: 12px 15px;
                text-align: left;
                font-weight: 600;
            }
            tbody tr:hover {
                background-color: #e2e2e2ff;
            }
            tbody td {
                padding: 12px 15px;
                border-bottom: 1px solid #ddd;
            }
            tbody tr:last-child td {
                border-bottom: none;
            }
            .btn {
                background-color: #0ec05eff;
                color: white;
                border: none;
                padding: 8px 14px;
                border-radius: 4px;
                cursor: pointer;
                font-weight: 600;
                transition: background-color 0.3s ease;
                text-decoration: none;
                display: inline-block;
                text-align: center;
                font-size: 14px;
                margin-top: 15px;
            }
            .btn:hover {
                background-color: #11e671f1;
            }
            form {
                margin: 0;
                display: inline;
            }
            form button.btn {
                padding: 6px 12px;
                font-size: 14px;
            }
        </style>
    </head>
    <body>

        <h2>Historial de: <?= htmlspecialchars($info['nombre_mascota']) ?> <small style="font-weight: normal; color: #494949ff;">(Dueño: <?= htmlspecialchars($info['nombre_cliente'] . ' ' . $info['apellido_cliente']) ?>)</small></h2>
        <!--Table-->
        <table>
            <thead>
                <tr>
                    <th>ID Diagnóstico</th>
                    <th>ID Visita</th>
                    <th>Fecha</th>
                    <th>Clientes</th>
                    <th>Costo</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($diagnosticos): ?><!--Inserta los datos-->
                    <?php foreach ($diagnosticos as $d): ?>
                        <tr>
                            <td><?= $d['id_diagnostico'] ?></td>
                            <td><?= $d['id_visita'] ?></td>
                            <td><?= $d['fecha'] ?></td>
                            <td><?= htmlspecialchars($d['clientes']) ?></td>
                            <td><?= number_format($d['costo'], 2) ?></td>
                            <td>
                                <form action="detalle_historial.php" method="get" style="display:inline;">
                                    <input type="hidden" name="id_diagnostico" value="<?= $d['id_diagnostico'] ?>">
                                    <button type="submit" class="btn">Detalles</button><!--Enlace al recibo-->
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 15px;">No hay diagnósticos registrados para esta mascota.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="../Funciones/Listar/listar_mascotas.php" class="btn">Volver</a>

    </body>
</html>
