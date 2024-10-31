<?php

require_once '../controllers/UserController.php';
// Esta línea incluye el archivo del controlador de usuarios,
// lo que permite acceder a la clase UserController y sus métodos 
// para manejar la lógica relacionada con los usuarios.


function UsersHandle($db)
{
    // Definición de la función UsersHandle que toma como parámetro
    // la conexión a la base de datos $db. Esta función maneja las
    // solicitudes relacionadas con los usuarios.

    // Ruta para iniciar sesión
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['REQUEST_URI'], '/login') !== false) {
        // Este bloque verifica si la solicitud es de tipo POST y
        // si la URI solicitada contiene '/login', indicando que
        // se está intentando iniciar sesión.

        error_log("Solicitud POST recibida en /login"); // Log para rastrear la solicitud de login
        // Se registra un mensaje en el log indicando que se recibió
        // una solicitud POST para iniciar sesión, útil para el
        // monitoreo y depuración.

        $userController = new UserController($db);
        // Se crea una instancia de la clase UserController, pasando 
        // la conexión a la base de datos. Esta instancia se utilizará
        // para llamar a métodos relacionados con la lógica de usuarios.

        $userController->login();
        // Se llama al método login() del controlador de usuarios,
        // que manejará el proceso de inicio de sesión.
    }

    // En caso de que la ruta no sea válida, enviar un mensaje de error
    else {
        // Si la solicitud no coincide con la ruta '/login', se ejecuta
        // este bloque de código.

        http_response_code(404);
        // Se establece el código de respuesta HTTP a 404, indicando que
        // la ruta solicitada no fue encontrada.

        error_log("Ruta no encontrada: " . $_SERVER['REQUEST_URI']); // Log de error
        // Se registra un mensaje en el log indicando que la ruta solicitada
        // no fue encontrada, lo que ayuda en la depuración.

        echo json_encode(['error' => 'Not Found']);
        // Se devuelve una respuesta en formato JSON con un mensaje de error,
        // informando al cliente que la ruta no fue encontrada.
    }
}
