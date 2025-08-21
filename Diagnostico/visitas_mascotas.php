<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//visitas_mascotas.php

require_once __DIR__ . '/../Conexion.php';

header('Content-Type: application/json');

//Busca por el id mascota
$id_mascota = $_GET['id_mascota'] ?? null;

if (!$id_mascota) { //Ausencia del id
    echo json_encode(['error' => 'ID de mascota no proporcionado']);
    exit;
}

//Extrae los datos
$sql = "SELECT id_visita, fecha_asignada, asunto 
        FROM visitas 
        WHERE id_mascota = ? 
        ORDER BY fecha_asignada DESC, hora DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_mascota]);
$visitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['visitas' => $visitas]);
?>