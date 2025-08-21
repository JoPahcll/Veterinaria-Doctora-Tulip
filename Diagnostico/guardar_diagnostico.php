<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//guardar_diagnostico.php

require_once __DIR__ . '/../Conexion.php';

//Datos
$id_visita = $_POST['id_visita'] ?? null;
$id_cliente = $_POST['id_cliente'] ?? null;
$peso = $_POST['peso'] ?? null;
$altura = $_POST['altura'] ?? null;
$observaciones = $_POST['observaciones'] ?? '';
$tratamiento = $_POST['tratamiento'] ?? '';
$costo_total = $_POST['costo_total'] ?? 0;

//Error por ausencia de datos
if (!$id_visita || !$id_cliente || !$peso || !$altura || !$costo_total) {
    die('Faltan datos obligatorios.');
}

//Obtener los datos
$sqlCheck = "
    SELECT 1 FROM visitas v
    JOIN mascotas_clientes mc ON v.id_mascota = mc.id_mascota
    WHERE v.id_visita = :id_visita AND mc.id_cliente = :id_cliente
";
$stmtCheck = $pdo->prepare($sqlCheck);
$stmtCheck->execute(['id_visita' => $id_visita, 'id_cliente' => $id_cliente]);
if (!$stmtCheck->fetchColumn()) {
    die('El cliente seleccionado no es dueño de la mascota en la visita.');
}

//Inserta los datos
$sqlInsert = "
    INSERT INTO diagnostico (id_visita, peso, altura, observaciones, tratamiento, costo_total)
    VALUES (:id_visita, :peso, :altura, :observaciones, :tratamiento, :costo_total)
";
$stmtInsert = $pdo->prepare($sqlInsert);
$exito = $stmtInsert->execute([
    'id_visita' => $id_visita,
    'peso' => $peso,
    'altura' => $altura,
    'observaciones' => $observaciones,
    'tratamiento' => $tratamiento,
    'costo_total' => $costo_total
]);

if ($exito) {
    // Actualizar estado de la visita a 'Concluida'
    $sqlUpdateEstado = "UPDATE visitas SET estado = 'Concluida' WHERE id_visita = :id_visita";
    $stmtUpdate = $pdo->prepare($sqlUpdateEstado);
    $stmtUpdate->execute(['id_visita' => $id_visita]);

    echo "Diagnóstico guardado correctamente.";
} else {
    echo "Error al guardar diagnóstico.";
}