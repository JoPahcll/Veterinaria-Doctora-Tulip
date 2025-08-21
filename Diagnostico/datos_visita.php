<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//datos_visita.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once __DIR__ . '/../Conexion.php';

$id_visita = $_GET['id_visita'] ?? null;//Usando el id visita

if (!$id_visita) {
    echo json_encode(['error' => 'ID de visita no proporcionado']);//Error al obtener el id
    exit;
}

//Selecciona los datos y los inserta
$sql = "SELECT c.nombre, c.apellido, c.telefono, c.correo
        FROM visitas v
        JOIN mascotas_clientes mc ON v.id_mascota = mc.id_mascota
        JOIN clientes c ON mc.id_cliente = c.id_cliente
        WHERE v.id_visita = ? LIMIT 1";

//Busqueda de errores
$stmt = $pdo->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Error preparando la consulta']);
    exit;
}

if (!$stmt->execute([$id_visita])) {
    echo json_encode(['error' => 'Error ejecutando la consulta']);
    exit;
}

$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    echo json_encode(['error' => 'No se encontraron datos del cliente para esta visita']);
    exit;
}

echo json_encode(['cliente' => $cliente]);
