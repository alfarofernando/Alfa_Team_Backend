<?php

require_once __DIR__ . '/../models/User.php';
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
        $username = $data['username'] ?? null;
        $isAdmin = $data['isAdmin'] ?? 0; // Valor predeterminado de 0 para isAdmin
        $image = $data['image'] ?? null;

        // Validar que los campos requeridos no estén vacíos
        if (!$email || !$password) {
            error_log("Create User failed: Missing email or password");
            http_response_code(400);
            echo json_encode(['message' => 'Correo electrónico y contraseña son requeridos.']);
            return;
        }

        // Log de validación antes de crear el usuario
        error_log("Creating user with email: $email");

        // Llamar al modelo para crear el usuario, pasando todos los campos
        $userCreated = $this->user->createUser(
            $email,
            $password,
            $username,
            $isAdmin,
            $image
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

    public function getUsersWithCourses()
    {
        //log de solicitud de obtencion de cursos de usuarios
        error_log("get users courses request received");

        $usersCourses = $this->user->getUsersWithCourses();
        header('Content-Type: application/json');

        //log de respuesta de la request
        error_log("returning data: " . json_encode($usersCourses));
        echo json_encode($usersCourses);
    }

    // Asignar curso a un usuario
    public function assignCourse(
        $userId,
        $courseId
    ) {
        try {
            // Log de depuración: datos recibidos
            error_log("Received data: userId = " . $userId . ", courseId = " . $courseId);

            $success = $this->user->assignCourse($userId, $courseId);

            if ($success) {
                echo json_encode(['message' => 'Curso asignado correctamente']);
            } else {
                echo json_encode(['error' => 'El curso ya está asignado a este usuario']);
            }
        } catch (Exception $e) {
            // Log de depuración: error en el flujo
            error_log("Error in assignCourse: " . $e->getMessage());
            echo json_encode(['error' => 'Error en la asignación: ' . $e->getMessage()]);
        }
    }
    public function revokeCourse($userId, $courseId)
    {
        try {
            // Verificar si el curso está asignado al usuario
            $success = $this->user->revokeCourse($userId, $courseId);

            if ($success) {
                echo json_encode(['message' => 'Curso desasignado correctamente']);
            } else {
                echo json_encode(['error' => 'El curso no está asignado a este usuario']);
            }
        } catch (Exception $e) {
            // Log de depuración: error en el flujo
            error_log("Error in revokeCourse: " . $e->getMessage());
            echo json_encode(['error' => 'Error en la desasignación: ' . $e->getMessage()]);
        }
    }
}
