<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//eliminar_cliente.php

require_once __DIR__ . '/../../Conexion.php';
require_once __DIR__ . '/../../clases/Cliente.php'; // Corrige mayúscula si tu sistema es case-sensitive

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        try {
            $exito = Cliente::eliminarPorId($pdo, $id);

            $sql = "DELETE m FROM mascotas m
                LEFT JOIN mascotas_clientes mc ON m.id_mascota = mc.id_mascota
                WHERE mc.id_cliente IS NULL";
            $pdo->exec($sql);

            //Manejo de errores
            if (!$exito) {
                Ejemplo: error_log("No se pudo eliminar el cliente con ID $id");
            }
        } catch (Exception $e) {
            error_log("Error eliminando cliente: " . $e->getMessage());
        }
    }
}

header("Location: ../Listar/listar_clientes.php?mensaje=eliminado");
exit;
