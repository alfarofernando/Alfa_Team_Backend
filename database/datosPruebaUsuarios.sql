-- Insertar datos de prueba en la tabla usuarios
INSERT INTO users (username, password, email, name, surname, age, isAdmin, permittedCourses, image)
VALUES
('jrodriguez', 'hashed_password_1', 'jrodriguez@example.com', 'Jorge', 'Rodriguez', 29, 0, JSON_ARRAY(1, 2, 5, 6), 'jrodriguez.jpg'),
('mlopez', 'hashed_password_2', 'mlopez@example.com', 'Maria', 'Lopez', 32, 1, JSON_ARRAY(3, 4), 'mlopez.jpg'),
('cperez', 'hashed_password_3', 'cperez@example.com', 'Carlos', 'Perez', 27, 0, JSON_ARRAY(1, 2, 4), NULL),
('adominguez', 'hashed_password_4', 'adominguez@example.com', 'Ana', 'Dominguez', 24, 1, JSON_ARRAY(1, 3, 5), 'adominguez.jpg'),
('fgarcia', 'hashed_password_5', 'fgarcia@example.com', 'Felipe', 'Garcia', 40, 0, JSON_ARRAY(2, 3, 6), 'fgarcia.jpg');
