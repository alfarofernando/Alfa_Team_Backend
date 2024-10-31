<?php

class UserController
{
    private $user;

    public function __construct($db)
    {
        $this->user = new User($db); // Crear una instancia del modelo
    }

    public function login()
    {
        // Obtén los datos de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);
        error_log("Datos de entrada para login: " . json_encode($data)); // Log de los datos recibidos

        $username = $data['username'] ?? null; // Captura el username
        $password = $data['password'] ?? null; // Captura la contraseña

        // Validar los datos
        if (!$username || !$password) {
            http_response_code(400);
            error_log("Datos inválidos: username o password faltantes."); // Log de error
            echo json_encode(['message' => 'Username y contraseña son requeridos.']);
            return;
        }

        // Llama al modelo para validar el usuario
        $user = $this->user->validateUser($username, $password);
        error_log("Resultado de validación del usuario: " . json_encode($user)); // Log del resultado

        if ($user) {
            // Si el usuario es válido, devuelve la información del usuario
            http_response_code(200);
            echo json_encode($user);
        } else {
            // Si no es válido, devuelve un mensaje de error
            http_response_code(401);
            error_log("Credenciales incorrectas para el usuario: " . $username); // Log de error
            echo json_encode(['message' => 'Credenciales incorrectas.']);
        }
    }
}
