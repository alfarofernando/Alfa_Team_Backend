-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS mi_base_de_datos;
USE mi_base_de_datos;

-- Crear la tabla usuarios
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    age INT NOT NULL,
    isAdmin TINYINT(1) DEFAULT 0,
    permittedLessons JSON DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (username),
    INDEX (email)
);

-- Crear la tabla lecciones
CREATE TABLE lecciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    curso_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    type ENUM('text', 'video') NOT NULL,
    content TEXT NOT NULL,
    INDEX (curso_id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
);

-- Crear la tabla cursos
CREATE TABLE cursos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    category VARCHAR(100) DEFAULT NULL,
    price DECIMAL(10, 2) NOT NULL,
    level INT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);