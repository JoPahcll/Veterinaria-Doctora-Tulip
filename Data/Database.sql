--Veterinaria
--José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
--Database.sql


--Crea la base de datos
CREATE DATABASE IF NOT EXISTS Veterinaria;
USE Veterinaria;

--Crea la tabla clientes (Fuerte)
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    correo VARCHAR(100) NOT NULL
);

--Crea la tabla mascotas (Debíl)
CREATE TABLE mascotas (
    id_mascota INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    especie VARCHAR(50) NOT NULL,
    raza VARCHAR(50) NOT NULL,
    edad INT UNSIGNED NOT NULL,
    sexo ENUM('Macho', 'Hembra') NOT NULL
);

--Crea la tabla visitas (Debíl)
CREATE TABLE visitas (
    id_visita INT AUTO_INCREMENT PRIMARY KEY,
    fecha_asignada DATE NOT NULL,
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    hora TIME NOT NULL,
    asunto VARCHAR(255) NOT NULL,
    id_mascota INT NOT NULL,
    FOREIGN KEY (id_mascota) REFERENCES mascotas(id_mascota) ON DELETE CASCADE,

    estado ENUM('Vigente', 'Concluida', 'Caducada') NOT NULL DEFAULT 'Vigente'
);

--Crea la tabla mascotas_clientes (Para la relación entre las mascotas y los clientes)
CREATE TABLE mascotas_clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mascota INT NOT NULL,
    id_cliente INT NOT NULL,
    FOREIGN KEY (id_mascota) REFERENCES mascotas(id_mascota) ON DELETE CASCADE,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE
);

--Crea la tabla diagnostico (Datos del diagnostico)
CREATE TABLE diagnostico (
    id_diagnostico INT AUTO_INCREMENT PRIMARY KEY,
    id_visita INT NOT NULL,
    peso DECIMAL(5,2) NOT NULL,
    altura DECIMAL(5,2) NOT NULL,
    observaciones TEXT,
    tratamiento TEXT,
    costo_total DECIMAL(10,2) NOT NULL DEFAULT 0,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_visita) REFERENCES visitas(id_visita) ON DELETE CASCADE
);
