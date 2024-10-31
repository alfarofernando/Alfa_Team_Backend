<?php

class UserController
// Se define la clase UserController, que maneja la lógica de control relacionada con los usuarios,
// como la autenticación y la gestión de datos de usuario.
{
    private $user;
    // Se declara una propiedad privada $user para almacenar una instancia del modelo User.

    public function __construct($db)
    // Este es el constructor de la clase UserController, que se llama al crear una nueva instancia de la clase.
    {
        $this->user = new User($db); // Crear una instancia del modelo
        // Se inicializa la propiedad $user creando una nueva instancia de la clase User,
        // pasando la conexión a la base de datos como argumento.
    }

    public function login()
    // Este método maneja el proceso de inicio de sesión de un usuario.
    {
        // Obtén los datos de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);
        // Se obtienen los datos del cuerpo de la solicitud HTTP (en formato JSON)
        // y se decodifican en un array asociativo.

        $username = $data['username'] ?? null; // Captura el username
        // Se intenta capturar el valor del nombre de usuario del array $data,
        // utilizando null como valor predeterminado si no existe.

        $password = $data['password'] ?? null; // Captura la contraseña
        // Se intenta capturar el valor de la contraseña del array $data,
        // utilizando null como valor predeterminado si no existe.

        // Validar los datos
        if (!$username || !$password) {
            // Verifica si el nombre de usuario o la contraseña no se proporcionaron.
            http_response_code(400);
            // Si faltan datos, se establece el código de respuesta HTTP a 400 (Bad Request).
            echo json_encode(['message' => 'Username y contraseña son requeridos.']);
            // Se devuelve un mensaje de error en formato JSON indicando que ambos campos son obligatorios.
            return; // Se termina la ejecución del método.
        }

        // Llama al modelo para validar el usuario
        $user = $this->user->validateUser($username, $password);
        // Se llama al método validateUser de la instancia de User,
        // pasando el nombre de usuario y la contraseña para validar las credenciales.

        if ($user) {
            // Si el usuario es válido, devuelve la información del usuario
            http_response_code(200);
            // Se establece el código de respuesta HTTP a 200 (OK).
            echo json_encode($user); // Ahora incluirá isAdmin
            // Se devuelve la información del usuario en formato JSON,
            // que incluye el nombre de usuario y si es administrador.
        } else {
            // Si no es válido, devuelve un mensaje de error
            http_response_code(401);
            // Se establece el código de respuesta HTTP a 401 (Unauthorized).
            echo json_encode(['message' => 'Credenciales incorrectas.']);
            // Se devuelve un mensaje de error en formato JSON indicando que las credenciales son incorrectas.
        }
    }
}
