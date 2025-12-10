CREATE DATABASE IF NOT EXISTS sistema_cuotas
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE sistema_cuotas;

CREATE TABLE ROL (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre TEXT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE USUARIO (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rut VARCHAR(20) UNIQUE NOT NULL,
    nombre TEXT NOT NULL,
    apellido TEXT NOT NULL,
    correo TEXT,
    telefono TEXT,
    contrasena TEXT NOT NULL,
    rol_id INT,
    FOREIGN KEY (rol_id) REFERENCES ROL(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE ALUMNO (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre TEXT NOT NULL,
    apellido TEXT NOT NULL,
    curso TEXT NOT NULL,
    apoderado_id INT,
    FOREIGN KEY (apoderado_id) REFERENCES USUARIO(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE CUOTA (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mes INT NOT NULL,
    anio INT NOT NULL,
    monto INT NOT NULL,
    estado_pago VARCHAR(50) DEFAULT 'Pendiente',
    apoderado_id INT,
    FOREIGN KEY (apoderado_id) REFERENCES USUARIO(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE PAGO (
    id INT AUTO_INCREMENT PRIMARY KEY,
    monto_pagado INT NOT NULL,
    fecha_pago DATE NOT NULL,
    cuota_id INT,
    usuario_id INT,
    FOREIGN KEY (cuota_id) REFERENCES CUOTA(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES USUARIO(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;