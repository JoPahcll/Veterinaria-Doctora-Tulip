--Veterinaria
--José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
--Login.sql

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contra VARCHAR(255) NOT NULL
);

INSERT INTO usuarios (usuario, contra) 
VALUES ('Admin', '12345678');
