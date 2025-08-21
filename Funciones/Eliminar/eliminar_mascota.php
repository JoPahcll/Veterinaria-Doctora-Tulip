<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//eliminar_mascota.php

require_once __DIR__ . '/../../Conexion.php';
require_once __DIR__ . '/../../clases/Mascota.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if ($id) {
        // Obtener objeto Mascota
        $mascota = Mascota::obtenerPorId($pdo, (int)$id);
        if ($mascota) {
            $mascota->eliminar($pdo);
        }
    }
}

header("Location: ../Listar/listar_mascotas.php");
exit;
?>
