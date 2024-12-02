-- Insertar datos de prueba en la tabla usuarios
INSERT INTO users (id, password, email, username, isAdmin, image, created_at) VALUES
(1, 'chiqui123', 'mlechmann@example.com', 'MirthaLegrand', 0, NULL, CURRENT_TIMESTAMP),
(2, 'su12345', 'susanagimenez@example.com', 'SusanaGimenez', 0, NULL, CURRENT_TIMESTAMP),
(3, 'rial789', 'jrial@example.com', 'JorgeRial', 0, NULL, CURRENT_TIMESTAMP),
(4, 'tinelli2024', 'mtinelli@example.com', 'MarceloTinelli', 1, NULL, CURRENT_TIMESTAMP),
(5, 'pampita1234', 'apampita@example.com', 'Pampita', 0, NULL, CURRENT_TIMESTAMP),
(6, 'juana5678', 'vjuana@example.com', 'JuanaViale', 0, NULL, CURRENT_TIMESTAMP),
(7, 'wanda9', 'wnara@example.com', 'WandaNara', 0, NULL, CURRENT_TIMESTAMP),
(8, 'zaira555', 'zaira@example.com', 'ZairaNara', 0, NULL, CURRENT_TIMESTAMP),
(9, 'burgos321', 'gburgos@example.com', 'GuillermoBurgos', 0, NULL, CURRENT_TIMESTAMP),
(10, 'gasalla333', 'agasalla@example.com', 'AntonioGasalla', 0, NULL, CURRENT_TIMESTAMP),
(11, 'lanata777', 'glanata@example.com', 'JorgeLanata', 1, NULL, CURRENT_TIMESTAMP),
(12, 'fantino01', 'afantino@example.com', 'AlejandroFantino', 0, NULL, CURRENT_TIMESTAMP),
(13, 'charly99', 'charly@example.com', 'CharlyGarcia', 0, NULL, CURRENT_TIMESTAMP),
(14, 'cerati2000', 'gcerati@example.com', 'GustavoCerati', 0, NULL, CURRENT_TIMESTAMP),
(15, 'abel3210', 'apintos@example.com', 'AbelPintos', 0, NULL, CURRENT_TIMESTAMP),
(16, 'solita123', 'ssilveyra@example.com', 'SoledadSilveyra', 0, NULL, CURRENT_TIMESTAMP),
(17, 'pachu123', 'pachu@example.com', 'PachuPena', 0, NULL, CURRENT_TIMESTAMP),
(18, 'milei2024', 'jmilei@example.com', 'JavierMilei', 1, NULL, CURRENT_TIMESTAMP),
(19, 'valeria888', 'vlynch@example.com', 'ValeriaLynch', 0, NULL, CURRENT_TIMESTAMP),
(20, 'araceli777', 'aaraceli@example.com', 'AraceliGonzalez', 0, NULL, CURRENT_TIMESTAMP);

INSERT INTO user_courses (user_id, course_id, subscribed_at) VALUES
(1, 1, CURRENT_TIMESTAMP),  -- MirthaLegrand se suscribe al curso 1
(1, 4, CURRENT_TIMESTAMP),  -- MirthaLegrand se suscribe al curso 4
(2, 2, CURRENT_TIMESTAMP),  -- SusanaGimenez se suscribe al curso 2
(3, 3, CURRENT_TIMESTAMP),  -- JorgeRial se suscribe al curso 3
(3, 1, CURRENT_TIMESTAMP),  -- JorgeRial se suscribe al curso 1
(4, 4, CURRENT_TIMESTAMP),  -- MarceloTinelli se suscribe al curso 4
(4, 5, CURRENT_TIMESTAMP),  -- MarceloTinelli se suscribe al curso 5
(5, 5, CURRENT_TIMESTAMP),  -- Pampita se suscribe al curso 5
(6, 6, CURRENT_TIMESTAMP),  -- JuanaViale se suscribe al curso 6
(7, 1, CURRENT_TIMESTAMP),  -- WandaNara se suscribe al curso 1
(7, 2, CURRENT_TIMESTAMP),  -- WandaNara se suscribe al curso 2
(8, 2, CURRENT_TIMESTAMP),  -- ZairaNara se suscribe al curso 2
(9, 3, CURRENT_TIMESTAMP),  -- GuillermoBurgos se suscribe al curso 3
(10, 4, CURRENT_TIMESTAMP), -- AntonioGasalla se suscribe al curso 4
(11, 5, CURRENT_TIMESTAMP), -- JorgeLanata se suscribe al curso 5
(11, 6, CURRENT_TIMESTAMP), -- JorgeLanata se suscribe al curso 6
(12, 6, CURRENT_TIMESTAMP), -- AlejandroFantino se suscribe al curso 6
(13, 1, CURRENT_TIMESTAMP), -- CharlyGarcia se suscribe al curso 1
(14, 3, CURRENT_TIMESTAMP), -- GustavoCerati se suscribe al curso 3
(15, 2, CURRENT_TIMESTAMP), -- AbelPintos se suscribe al curso 2
(16, 5, CURRENT_TIMESTAMP), -- SoledadSilveyra se suscribe al curso 5
(16, 6, CURRENT_TIMESTAMP), -- SoledadSilveyra se suscribe al curso 6
(17, 1, CURRENT_TIMESTAMP), -- PachuPena se suscribe al curso 1
(18, 4, CURRENT_TIMESTAMP), -- JavierMilei se suscribe al curso 4
(19, 2, CURRENT_TIMESTAMP), -- ValeriaLynch se suscribe al curso 2
(20, 1, CURRENT_TIMESTAMP); -- AraceliGonzalez se suscribe al curso 1
