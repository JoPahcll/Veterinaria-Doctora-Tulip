<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//Conexión.php

// Conexión con la base de datos
const DB_HOST = 'localhost';
const DB_NAME = 'veterinaria';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';

// Conexion PDO
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Modo de errores
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Formato por defecto de los resultados
            PDO::ATTR_EMULATE_PREPARES => false // Usar consultas preparadas reales
        ]
    );
} catch (PDOException $e) {// Error de la conexión
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
