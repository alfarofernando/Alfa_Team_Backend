<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Manejo de opciones de preflight (CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuración de la base de datos
$host = 'localhost';
$db = 'alfateam';
$user = 'root'; // Cambia esto si tienes otro usuario
$pass = ''; // Cambia esto si tienes otra contraseña

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(['message' => 'Error de conexión a la base de datos: ' . $conn->connect_error]));
}

// Manejo de la solicitud de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    // Preparar la consulta
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        echo json_encode(['message' => 'Inicio de sesión exitoso']);
        http_response_code(200);
    } else {
        echo json_encode(['message' => 'Credenciales incorrectas']);
        http_response_code(401);
    }

    $stmt->close();
    $conn->close();
    exit();
}

// Respuesta por defecto
echo json_encode(['message' => 'Método no permitido']);
http_response_code(405);
