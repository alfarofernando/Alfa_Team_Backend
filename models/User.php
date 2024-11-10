<?php
class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function validateUser($email, $password)
    {
        // Log de inicio de la validación
        error_log("Validating user with email: $email");

        $query = "SELECT id, email, password, isAdmin FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Log si se encuentra el usuario
            error_log("User found: " . json_encode($user));

            // Comparar la contraseña directamente con texto plano
            if ($password === $user['password']) {
                error_log("Password match for user: $email");
                return [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'isAdmin' => (bool)$user['isAdmin']
                ];
            } else {
                // Log si la contraseña no coincide
                error_log("Password mismatch for user: $email");
            }
        } else {
            // Log si no se encuentra el usuario
            error_log("User not found for email: $email");
        }

        return false;
    }

    public function createUser($email, $password, $name, $surname, $age, $isAdmin, $image, $permittedCourses)
    {
        // Log de la creación del usuario
        error_log("Attempting to create user with email: $email");

        // Consulta para insertar el nuevo usuario
        $query = "INSERT INTO users (email, password, name, surname, age, isAdmin, image, permittedCourses)
              VALUES (:email, :password, :name, :surname, :age, :isAdmin, :image, :permittedCourses)";
        $stmt = $this->db->prepare($query);

        // Vincular los parámetros
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password); // Contraseña en texto plano
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':isAdmin', $isAdmin);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':permittedCourses', $permittedCourses); // Vincular permittedCourses

        $result = $stmt->execute();

        // Log el resultado de la operación
        if ($result) {
            error_log("User created successfully: $email");
        } else {
            error_log("Failed to create user: $email");
        }

        return $result;
    }

    public function getAllUsers()
    {
        // Log para obtener todos los usuarios
        error_log("Fetching all users");

        $query = "SELECT id, email , permittedCourses FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Log de usuarios obtenidos
        error_log("Fetched users: " . json_encode($users));

        return $users;
    }

    public function getUserData($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // En lugar de hacer echo aquí, devolvemos los datos del usuario o `false` si no se encuentra
        return $user ?: false;
    }

    public function updateUserData($userId, $userData)
    {
        // Crear la consulta SQL dinámicamente
        $sql = "UPDATE users SET ";
        $params = [];
        foreach ($userData as $key => $value) {
            $sql .= "$key = ?, ";
            $params[] = $value;
        }
        $sql = rtrim($sql, ', ') . " WHERE id = ?";
        $params[] = $userId;

        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function getUserPermittedCourses($userId)
    {
        if (empty($userId)) {
            throw new Exception('El ID de usuario es requerido');
        }

        $stmt = $this->db->prepare("SELECT permittedCourses FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $courseIds = json_decode($user['permittedCourses'], true);
            if (!empty($courseIds)) {
                $placeholders = implode(',', array_fill(0, count($courseIds), '?'));
                $stmtCourses = $this->db->prepare("SELECT title, description, category, level FROM courses WHERE id IN ($placeholders)");
                $stmtCourses->execute($courseIds);
                return $stmtCourses->fetchAll(PDO::FETCH_ASSOC);
            }
            throw new Exception('No hay cursos permitidos asignados al usuario');
        }
        throw new Exception('Usuario no encontrado');
    }
}
