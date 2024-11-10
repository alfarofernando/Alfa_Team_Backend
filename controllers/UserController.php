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

        // Log de entrada de datos
        error_log("Login request: " . json_encode($data));

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        // Validar los datos
        if (!$email || !$password) {
            error_log("Login failed: Missing email or password");
            http_response_code(400);
            echo json_encode(['message' => 'Email y contraseña son requeridos.']);
            return;
        }

        // Log de validación
        error_log("Attempting to validate user with email: $email");

        // Llamar al modelo para validar el usuario
        $user = $this->user->validateUser($email, $password);

        if ($user) {
            error_log("User validated successfully: " . json_encode($user));
            // Solo devolver los datos esenciales
            $userData = [
                'id' => $user['id'],
                'email' => $user['email'],
                'isAdmin' => $user['isAdmin']
            ];
            http_response_code(200);
            echo json_encode($userData); // Devolver solo los datos esenciales del usuario
        } else {
            error_log("Login failed: Invalid credentials for email: $email");
            http_response_code(401);
            echo json_encode(['message' => 'Credenciales incorrectas.']);
        }
    }

    public function createUser()
    {
        // Obtener los datos JSON de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);

        // Log de entrada de datos
        error_log("Create User request: " . json_encode($data));

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
            error_log("Create User failed: Missing email or password");
            http_response_code(400);
            echo json_encode(['message' => 'Correo electrónico y contraseña son requeridos.']);
            return;
        }

        // Log de validación antes de crear el usuario
        error_log("Creating user with email: $email");

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
            error_log("User created successfully: $email");
            http_response_code(201);
            echo json_encode(['message' => 'Usuario creado exitosamente.']);
        } else {
            error_log("Create User failed: Error creating user with email: $email");
            http_response_code(500);
            echo json_encode(['message' => 'Error al crear el usuario.']);
        }
    }

    public function getUsers()
    {
        // Log de solicitud de obtener usuarios
        error_log("Get Users request received");

        $users = $this->user->getAllUsers();
        header('Content-Type: application/json');

        // Log de respuesta de usuarios
        error_log("Returning users: " . json_encode($users));

        echo json_encode($users);
    }

    public function getUserData($userId)
    {
        // Llamar al método getUserData del modelo
        $user = $this->user->getUserData($userId);

        // Verificar si se obtuvo el usuario y devolver la respuesta adecuada
        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    }

    public function updateUser($userId, $data)
    {
        // Llamar al modelo para actualizar los datos del usuario
        if ($this->user->updateUserData($userId, $data)) {
            return ['status' => 200, 'message' => 'User data updated successfully'];
        } else {
            return ['status' => 500, 'message' => 'Failed to update user data'];
        }
    }

    public function getUserPermittedCourses($userId)
    {
        try {
            $courses = $this->user->getUserPermittedCourses($userId);
            echo json_encode(['permittedCourses' => $courses]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
