<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//detalle_historial.php

require_once __DIR__ . '/../Conexion.php';

//Error de registrado
if (!isset($_GET['id_diagnostico']) || empty($_GET['id_diagnostico'])) {
    die("ID de diagnóstico no fue especificado o no fue posible obtenerlo.");
}

$id_diagnostico = $_GET['id_diagnostico'];//Obtiene mediante el id historial

//Obtiene los datos y los inserta en la ubicación destinada
$sql = "
    SELECT 
        d.id_diagnostico, d.peso, d.altura, d.observaciones, d.tratamiento, d.costo_total, d.creado_en, v.fecha_asignada AS fecha,
        c.id_cliente, c.cedula, c.nombre AS nombre_cliente, c.apellido AS apellido_cliente, c.telefono, c.correo,
        m.id_mascota, m.nombre AS nombre_mascota, m.especie, m.raza, m.edad, m.sexo,
        v.id_visita, v.fecha_asignada, v.fecha_asignacion, v.hora, v.asunto
    FROM diagnostico d
    JOIN visitas v ON d.id_visita = v.id_visita
    JOIN mascotas m ON v.id_mascota = m.id_mascota
    JOIN mascotas_clientes mc ON m.id_mascota = mc.id_mascota
    JOIN clientes c ON mc.id_cliente = c.id_cliente
    WHERE d.id_diagnostico = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_diagnostico]);
$detalle = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$detalle) {
    die("Diagnóstico no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <title>Factura</title>
        <style>/*Diseño*/
            body {
                font-family: Arial, sans-serif;
                max-width: 700px;
                margin: 20px auto;
                padding: 15px;
                border: 1px solid #ccc;
                background: #fff;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            h2 {
                text-align: center;
                margin-bottom: 30px;
            }
            h3 {
                border-bottom: 2px solid #000000ff;
                padding-bottom: 5px;
                margin-top: 30px;
                margin-bottom: 15px;
                color: #000000ff;
            }
            .info-row {
                display: flex;
                justify-content: space-between;
                padding: 6px 10px;
                border-bottom: 1px solid #eee;
                font-size: 14px;
            }
            .info-row:nth-child(even) {
                background-color: #f9f9f9;
            }
            .info-label {
                font-weight: 600;
                color: #333;
                width: 45%;
            }
            .info-value {
                width: 50%;
                text-align: right;
                word-wrap: break-word;
            }
            .observaciones, .tratamiento {
                white-space: pre-wrap;
                background: #f1f1f1;
                padding: 10px;
                border-radius: 4px;
                margin-top: 5px;
                margin-bottom: 10px;
                font-size: 14px;
                line-height: 1.4;
            }
            .firmas-container {
                display: flex;
                justify-content: space-between;
                margin-top: 50px;
                gap: 20px;
            }
            .firma-box {
                width: 48%;
                border-top: 1px solid #333;
                height: 70px;
                text-align: center;
                padding-top: 8px;
                font-weight: 600;
                font-size: 14px;
                color: #555;
            }
            a.btn {
                display: inline-block;
                margin-top: 40px;
                padding: 10px 18px;
                background-color: #000000ff;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                font-weight: 600;
                transition: background-color 0.3s ease;
            }
            a.btn:hover {
                background-color: #000000ff;
            }
        </style>
    </head>
    <body>
        <h2>Diagnóstico - Revisión #<?= $detalle['id_diagnostico'] ?></h2><!--Titulo-->

        <h3>Datos del Cliente</h3><!--Datos del cliente-->
        <div class="info-row"><div class="info-label">ID Cliente</div><div class="info-value"><?= $detalle['id_cliente'] ?></div></div>
        <div class="info-row"><div class="info-label">Cédula</div><div class="info-value"><?= htmlspecialchars($detalle['cedula']) ?></div></div>
        <div class="info-row"><div class="info-label">Nombre</div><div class="info-value"><?= htmlspecialchars($detalle['nombre_cliente']) ?></div></div>
        <div class="info-row"><div class="info-label">Apellido</div><div class="info-value"><?= htmlspecialchars($detalle['apellido_cliente']) ?></div></div>
        <div class="info-row"><div class="info-label">Teléfono</div><div class="info-value"><?= htmlspecialchars($detalle['telefono']) ?></div></div>
        <div class="info-row"><div class="info-label">Correo</div><div class="info-value"><?= htmlspecialchars($detalle['correo']) ?></div></div>

        <h3>Datos de la Mascota</h3><!--Datos de la mascota-->
        <div class="info-row"><div class="info-label">ID Mascota</div><div class="info-value"><?= $detalle['id_mascota'] ?></div></div>
        <div class="info-row"><div class="info-label">Nombre</div><div class="info-value"><?= htmlspecialchars($detalle['nombre_mascota']) ?></div></div>
        <div class="info-row"><div class="info-label">Especie</div><div class="info-value"><?= htmlspecialchars($detalle['especie']) ?></div></div>
        <div class="info-row"><div class="info-label">Raza</div><div class="info-value"><?= htmlspecialchars($detalle['raza']) ?></div></div>
        <div class="info-row"><div class="info-label">Edad</div><div class="info-value"><?= $detalle['edad'] ?></div></div>
        <div class="info-row"><div class="info-label">Sexo</div><div class="info-value"><?= htmlspecialchars($detalle['sexo']) ?></div></div>

        <h3>Datos de la Visita</h3><!--Datos de la visita-->
        <div class="info-row"><div class="info-label">ID Visita</div><div class="info-value"><?= $detalle['id_visita'] ?></div></div>
        <div class="info-row"><div class="info-label">Fecha asignada (visita)</div><div class="info-value"><?= $detalle['fecha_asignada'] ?></div></div>
        <div class="info-row"><div class="info-label">Fecha de asignación (registro)</div><div class="info-value"><?= $detalle['fecha_asignacion'] ?></div></div>
        <div class="info-row"><div class="info-label">Hora</div><div class="info-value"><?= $detalle['hora'] ?></div></div>
        <div class="info-row"><div class="info-label">Asunto</div><div class="info-value"><?= htmlspecialchars($detalle['asunto']) ?></div></div>

        <h3>Datos del Diagnóstico</h3><!--Datos del diagnóstico-->
        <div class="info-row"><div class="info-label">Peso</div><div class="info-value"><?= $detalle['peso'] ?></div></div>
        <div class="info-row"><div class="info-label">Altura</div><div class="info-value"><?= $detalle['altura'] ?></div></div>

        <div class="info-label" style="margin-top:15px; font-weight:600;">Observaciones</div>
        <div class="observaciones"><?= nl2br(htmlspecialchars($detalle['observaciones'])) ?></div>

        <div class="info-label" style="margin-top:15px; font-weight:600;">Tratamiento</div>
        <div class="tratamiento"><?= htmlspecialchars($detalle['tratamiento']) ?></div>

        <div class="info-row"><div class="info-label">Costo</div><div class="info-value"><?= number_format($detalle['costo_total'], 2) ?></div></div>
        <div class="info-row"><div class="info-label">Fecha del diagnóstico</div><div class="info-value"><?= $detalle['fecha'] ?></div></div>
        <div class="info-row"><div class="info-label">Fecha de creación del recibo</div><div class="info-value"><?= $detalle['creado_en'] ?></div></div>

        <h3>Datos del Médico</h3><!--Datos de la doctora-->
        <div class="info-row"><div class="info-label">Atendido por:</div><div class="info-value">Lic. Tulipan Palacios Ferrer</div></div>
        <div class="info-row"><div class="info-label">Cédula</div><div class="info-value">143099015</div></div>
        <div class="info-row"><div class="info-label">Consultorio</div><div class="info-value">Veterinaria Doc Tulip</div></div>
        <div class="info-row"><div class="info-label">Número telefónico</div><div class="info-value">3266-7889</div></div>
        <div class="info-row"><div class="info-label">Correo</div><div class="info-value">veterinaria.doctulip@gmail.com</div></div>
        <div class="info-row"><div class="info-label">Horario de atención</div><div class="info-value">Lunes-Viernes: 8:30 am - 6:00 pm / Sábados: 9:00 am - 5:00 pm</div></div>
        <div class="info-row"><div class="info-label">Ubicación</div><div class="info-value">San José, Alajuelita, 200 metros este y 60 sur de la iglesia La Parroquia Santo Cristo de Esquipulas</div></div>

        <div class="firmas-container">
            <div class="firma-box">Firma</div>
            <div class="firma-box">Firma del cliente</div>
        </div>

        <a href="historial.php?id_mascota=<?= $detalle['id_mascota'] ?>" class="btn">Volver al Historial</a>
        <a href="exportar_txt.php?id_diagnostico=<?= $detalle['id_diagnostico'] ?>" class="btn" style="background-color: #000000ff;">Imprimir</a>

    </body>
</html>