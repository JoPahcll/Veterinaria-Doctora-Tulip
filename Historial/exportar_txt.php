<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//exportar_txt.php

require_once __DIR__ . '/../Conexion.php';

if (!isset($_GET['id_diagnostico']) || empty($_GET['id_diagnostico'])) {
    die("ID de diagnóstico no fue especificado.");
}

$id_diagnostico = $_GET['id_diagnostico'];

//Selecciona los datos y inserta los que hagan falta
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

if (!$detalle) {//Mensaje de error
    die("Diagnóstico no encontrado.");
}

//Todos los datos
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="diagnostico_'.$detalle['id_diagnostico'].'.txt"');

echo "Detalle del Diagnóstico #{$detalle['id_diagnostico']}\n\n";

echo "Datos del Cliente\n";
echo "ID Cliente: {$detalle['id_cliente']}\n";
echo "Cédula: {$detalle['cedula']}\n";
echo "Nombre: {$detalle['nombre_cliente']} {$detalle['apellido_cliente']}\n";
echo "Teléfono: {$detalle['telefono']}\n";
echo "Correo: {$detalle['correo']}\n\n";

echo "Datos de la Mascota\n";
echo "ID Mascota: {$detalle['id_mascota']}\n";
echo "Nombre: {$detalle['nombre_mascota']}\n";
echo "Especie: {$detalle['especie']}\n";
echo "Raza: {$detalle['raza']}\n";
echo "Edad: {$detalle['edad']}\n";
echo "Sexo: {$detalle['sexo']}\n\n";

echo "Datos de la Visita\n";
echo "ID Visita: {$detalle['id_visita']}\n";
echo "Fecha asignada: {$detalle['fecha_asignada']}\n";
echo "Fecha de registro: {$detalle['fecha_asignacion']}\n";
echo "Hora: {$detalle['hora']}\n";
echo "Asunto: {$detalle['asunto']}\n\n";

echo "Diagnóstico\n";
echo "Peso: {$detalle['peso']}\n";
echo "Altura: {$detalle['altura']}\n";
echo "Observaciones: {$detalle['observaciones']}\n";
echo "Tratamiento: {$detalle['tratamiento']}\n";
echo "Costo total: {$detalle['costo_total']}\n";
echo "Fecha del diagnóstico: {$detalle['fecha']}\n";
echo "Fecha de creación: {$detalle['creado_en']}\n\n";

echo "Datos del funcionario\n";
echo "Atendido por: Lic. Tulipan Palacios Ferrer\n";
echo "Cédula: 143099015\n";
echo "Consultorio: Veterinaria Doc Tulip\n";
echo "Teléfono: 3266-7889\n";
echo "Correo: veterinaria.doctulip@gmail.com\n";
echo "Horario: Lunes-Viernes 8:30 am - 6:00 pm / Sábados 9:00 am - 5:00 pm\n";
echo "Ubicación: San José, Alajuelita, 200 metros este y 60 sur de la iglesia La Parroquia Santo Cristo de Esquipulas\n";
