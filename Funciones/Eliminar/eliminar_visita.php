<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//eliminar_visita.php

require_once __DIR__ . '/../../Conexion.php';

$id = $_POST['id'] ?? null;//Busca usando el id de la visita

if (!$id) {//Ausencia del id
    die("ID de visita no proporcionado.");
}

//Elimina usando el id como guía
$sql = "DELETE FROM visitas WHERE id_visita = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

//Ubicación
header("Location: ../Listar/listar_visitas.php");
exit;
?>