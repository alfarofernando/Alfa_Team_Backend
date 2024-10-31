<?php

// Cargar la configuración de la base de datos
require_once '../config/database.php';

// Habilitar CORS
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Manejo de las solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Cargar los modelos y controladores
require_once '../models/User.php';
require_once '../controllers/UserController.php';

// Crear una instancia de la conexión a la base de datos
$db = Database::getConnection();
header('Content-Type: application/json');

// Manejo de la ruta raíz
if ($_SERVER['REQUEST_URI'] === '/') {
    echo json_encode(['message' => 'API is running']);
    exit;
}

// Ruta para iniciar sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['REQUEST_URI'], '/login') !== false) {
    error_log("Solicitud POST recibida en /login"); // Log para rastrear la solicitud de login
    $userController = new UserController($db);
    $userController->login();
} else {
    // En caso de que la ruta no sea válida, enviar un mensaje de error
    http_response_code(404);
    error_log("Ruta no encontrada: " . $_SERVER['REQUEST_URI']); // Log de error
    echo json_encode(['error' => 'Not Found']);
}
