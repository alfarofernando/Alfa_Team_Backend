<?php

// Cargar configuración de la base de datos, CORS y controladores
require_once '../config/database.php';
require_once '../config/corsConfig.php';
require_once '../models/User.php';
require_once '../models/Course.php';
require_once '../controllers/UserController.php';
require_once '../controllers/CourseController.php';

// Crear instancia de conexión a la base de datos
$db = Database::getConnection();
header('Content-Type: application/json');

// Manejo de solicitudes OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Función para manejar las rutas
function handleRequest($db)
{
    $uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'POST' && strpos($uri, '/login') !== false) {
        (new UserController($db))->login();
    } elseif ($method === 'POST' && strpos($uri, '/createUser') !== false) {
        (new UserController($db))->createUser();
    } elseif ($method === 'GET' && strpos($uri, '/getCourses') !== false) {
        (new CourseController($db))->getCourses();
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada']);
    }
}

// Si la solicitud es para la raíz
if ($_SERVER['REQUEST_URI'] === '/') {
    echo json_encode(['message' => 'API is running']);
} else {
    handleRequest($db);
}
