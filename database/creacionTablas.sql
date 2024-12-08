-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS alfateam;
USE alfateam;

-- Crear la tabla usuarios
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) DEFAULT NULL,
    isAdmin TINYINT(1) DEFAULT 0,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Crear la tabla lecciones con las nuevas columnas
CREATE TABLE lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,         -- Identificador único para cada lección
    course_id INT NOT NULL,                    -- Relación con la tabla courses
    title VARCHAR(255) NOT NULL,               -- Título de la lección
    type ENUM('text', 'video') NOT NULL,       -- Tipo de lección: texto o video
    content TEXT NOT NULL,                     -- Contenido de la lección
    order_number INT NOT NULL DEFAULT 0,       -- Orden de la lección en el curso
    is_enabled BOOLEAN NOT NULL DEFAULT TRUE,  -- Estado de habilitación de la lección
    FOREIGN KEY (course_id) REFERENCES courses(id) -- Clave foránea para relacionar con courses
);



-- Crear la tabla cursos
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100) DEFAULT NULL,
    price DECIMAL(10,2) NOT NULL,
    level INT NOT NULL,
    is_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);



-- Crear la tabla intermedia user_courses
CREATE TABLE user_courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Crea la tabla para almacenar rutas de imagenes asociada a los cursos
CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identificador único para cada imagen
    course_id INT NOT NULL,            -- ID del curso asociado
    image_path VARCHAR(255) NOT NULL,  -- Ruta de la imagen
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha y hora de creación
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Fecha y hora de última actualización
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE -- Relación con la tabla courses
);



