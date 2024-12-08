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
    // get all courses from ddbb
    elseif ($method === 'GET' && strpos($uri, '/getAllCourses') !== false) {
        (new CourseController($db))->getAllCourses();
    }
    // get a single course by id 
    elseif ($method === 'GET' && preg_match('/\/course\/(\d+)/', $uri, $matches)) {
        $id = $matches[1]; // Captura el ID del curso desde la URL
        (new CourseController($db))->getCourseById($id);
    }
    // add a course
    elseif ($method === 'POST' && $uri === '/addCourse') {
        (new CourseController($db))->addCourse();
    }
    // update a selected course
    elseif ($method === 'PUT' && preg_match('/\/updateCourse\/(\d+)/', $uri, $matches)) {
        $id = $matches[1];  // Obtén el ID del curso desde la URL
        $data = json_decode(file_get_contents('php://input'), true);  // Obtener los datos enviados en el cuerpo de la solicitud
        (new CourseController($db))->updateCourse($id);  // Llama al método para actualizar el curso
    }
    // delete a selected course
    elseif (
        $method === 'DELETE' && preg_match('/\/deleteCourse\/(\d+)/', $uri, $matches)
    ) {
        $id = $matches[1];
        error_log("attempting delete course with id: $id");
        (new CourseController($db))->deleteCourse($id);
        //get all courses for a single user by id
    } elseif ($method === 'GET' && preg_match('/\/getCoursesByUserId\/(\d+)/', $uri, $matches)) {
        $id = $matches[1];
        (new CourseController($db))->getCoursesByUserId($id);
    } //disable selected course
    elseif ($method === 'PUT' && preg_match('/\/disableCourse\/(\d+)/', $uri, $matches)) {
        $id = $matches[1];
        (new CourseController($db))->disableCourse($id);
    }

    // rutas de lecciones
    elseif ($method === 'GET' && preg_match('/\/lessons\/course\/(\d+)/', $uri, $matches)) {
        $courseId = $matches[1];
        (new Lesson($db))->getLessonsByCourse($courseId);
    } elseif ($method === 'POST' && $uri === '/addLesson') {
        (new LessonController($db))->addLesson();
    } elseif ($method === 'PUT' && $uri === '/updateLesson') {
        (new LessonController($db))->updateLesson();
    } elseif ($method === 'DELETE' && preg_match('/\/lesson\/delete\/(\d+)/', $uri, $matches)) {
        $lessonId = $matches[1];
        (new Lesson($db))->deleteLesson($lessonId);
    }

    // rutas para imagenes
    // Crear una nueva imagen asociada a un curso
    elseif ($method === 'POST' && strpos($uri, '/uploadImage') !== false) {
        (new ImageController($db))->createImage();
    }
    // Actualizar una imagen existente de un curso
    elseif ($method === 'POST' && preg_match('/\/updateImage\/(\d+)/', $uri, $matches)) {
        $id = $matches[1];
        (new ImageController($db))->updateImage($id);
    }
    //ruta con error 404
    else {
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
