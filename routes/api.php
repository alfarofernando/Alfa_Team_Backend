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

    // Rutas de usuarios
    if ($method === 'POST' && strpos($uri, '/login') !== false) {
        (new UserController($db))->login();
    } elseif ($method === 'POST' && strpos($uri, '/createUser') !== false) {
        (new UserController($db))->createUser();
    } elseif ($method === 'POST' && preg_match('/^\/user$/', $uri)) {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['userId'])) {
            (new UserController($db))->getUserData($data['userId']);
        } else {
            echo json_encode(["error" => "userId es requerido"]);
        }
    } elseif ($method === 'POST' && preg_match('/^\/updateUserData$/', $uri)) {
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["error" => "Invalid JSON format"]);
            exit;
        }
        $userId = $inputData['userId'] ?? null;
        $userData = $inputData['userData'] ?? null;

        if (!$userId || !$userData) {
            echo json_encode(["error" => "User ID or data missing"]);
            exit;
        }
        $response = (new UserController($db))->updateUser($userId, $userData);
        echo json_encode(['message' => $response['message']]);
    } elseif ($method === 'GET' && strpos($uri, '/getUsersWithCourses') !== false) {
        (new UserController($db))->getUsersWithCourses();
    } elseif (
        $method === 'POST' && strpos($uri, '/assignCourse') !== false
    ) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['user_id']) && isset($data['course_id'])) {
            // Asegúrate de que 'course_id' está presente y es válido
            (new UserController($db))->assignCourse($data['user_id'], $data['course_id']);
        } else {
            error_log("Datos incompletos: " . json_encode($data));
            echo json_encode(['error' => 'Datos incompletos']);
        }
    } elseif (
        $method === 'POST' && strpos($uri, '/revokeCourse') !== false
    ) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['user_id']) && isset($data['course_id'])) {
            // Asegúrate de que 'course_id' está presente y es válido
            (new UserController($db))->revokeCourse($data['user_id'], $data['course_id']);
        } else {
            error_log("Datos incompletos: " . json_encode($data));
            echo json_encode(['error' => 'Datos incompletos']);
        }
    }



    // Rutas de cursos
    elseif ($method === 'GET' && strpos($uri, '/getCourses') !== false) {
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
