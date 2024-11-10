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

// Función para manejar las rutas
function handleRequest($db)
{
    $uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    //users routes
    if ($method === 'POST' && strpos($uri, '/login') !== false) {
        (new UserController($db))->login();
    } elseif ($method === 'POST' && strpos($uri, '/createUser') !== false) {
        (new UserController($db))->createUser();
    } elseif ($method === 'GET' && strpos($uri, '/getAllUsers') !== false) {
        (new UserController($db))->getUsers();
    } elseif ($method === 'POST' && preg_match('/^\/user$/', $uri)) {
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['userId'])) {
            $userId = $data['userId'];
            (new UserController($db))->getUserData($userId);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "userId es requerido"]);
        }
    } elseif ($method === 'POST' && preg_match('/^\/updateUserData$/', $uri)) {
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON format"]);
            exit;
        }

        $userId = $inputData['userId'] ?? null;
        $userData = $inputData['userData'] ?? null;

        if (!$userId || !$userData) {
            http_response_code(400);
            echo json_encode(["error" => "User ID or data missing"]);
            exit;
        }

        $response =  (new UserController($db))->updateUser($userId, $userData);
        http_response_code($response['status']);
        echo json_encode(['message' => $response['message']]);
    }
    //courses routes

    elseif ($method === 'GET' && strpos($uri, '/getCourses') !== false) {
        (new CourseController($db))->getCourses();
    } elseif ($method === 'POST' && strpos($uri, '/getUserPermittedCourses') !== false) {
        // Obtener los datos del cuerpo de la solicitud
        $inputData = json_decode(file_get_contents("php://input"), true);
        $userId = $inputData['userId'] ?? null;  // Obtener el userId desde los datos enviados en la solicitud

        // Verificar si el userId está presente
        if ($userId !== null) {
            // Crear una instancia del controlador
            $userController = new UserController($db);

            // Llamar al método del controlador para obtener los cursos permitidos, pasando el userId como parámetro
            $userController->getUserPermittedCourses($userId);  // Asegurarte de pasar el userId
        } else {
            // Si el userId no se proporciona, devolver un error
            echo json_encode(['error' => 'El ID de usuario es requerido']);
        }
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
