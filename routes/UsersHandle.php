<?php

require_once '../controllers/UserController.php';

function UsersHandle($db)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['REQUEST_URI'], '/login') !== false) {
        error_log("Solicitud POST recibida en /login");
        $userController = new UserController($db);
        $userController->login();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['REQUEST_URI'], '/createUser') !== false) {
        error_log("Solicitud POST recibida en /createUser");
        $userController = new UserController($db);
        $userController->createUser();
    } else {
        http_response_code(404);
        error_log("Ruta no encontrada: " . $_SERVER['REQUEST_URI']);
        echo json_encode(['error' => 'Not Found']);
    }
}
