<?php
class UserController
{
    private $user;

    public function __construct($db)
    {
        $this->user = new User($db); // Crear una instancia del modelo User
    }

    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $email = $data['email'] ?? null;  // Cambiar de 'username' a 'email'
        $password = $data['password'] ?? null;

        // Validar los datos
        if (!$email || !$password) {
            http_response_code(400);
            echo json_encode(['message' => 'Email y contraseña son requeridos.']);
            return;
        }

        // Llamar al modelo para validar el usuario
        $user = $this->user->validateUser($email, $password);  // Pasar email en lugar de username

        if ($user) {
            http_response_code(200);
            echo json_encode($user); // Incluye el email y el estado de admin
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Credenciales incorrectas.']);
        }
    }


    public function createUser()
    {
        // Obtener los datos JSON de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);

        // Asignar los valores de los campos necesarios y opcionales
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $name = $data['name'] ?? null;
        $surname = $data['surname'] ?? null;
        $age = $data['age'] ?? null;
        $isAdmin = $data['isAdmin'] ?? 0; // Valor predeterminado de 0 para isAdmin
        $image = $data['image'] ?? null;
        $permittedCourses = $data['permittedCourses'] ?? null; // Asignar permitido si lo pasa

        // Validar que los campos requeridos no estén vacíos
        if (!$email || !$password) {
            http_response_code(400);
            echo json_encode(['message' => 'Correo electrónico y contraseña son requeridos.']);
            return;
        }

        // Llamar al modelo para crear el usuario, pasando todos los campos (incluido permittedCourses)
        $userCreated = $this->user->createUser(
            $email,
            $password,
            $name,
            $surname,
            $age,
            $isAdmin,
            $image,
            $permittedCourses // Se pasa también permittedCourses
        );

        // Verificar si el usuario fue creado exitosamente
        if ($userCreated) {
            http_response_code(201);
            echo json_encode(['message' => 'Usuario creado exitosamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al crear el usuario.']);
        }
    }
}
