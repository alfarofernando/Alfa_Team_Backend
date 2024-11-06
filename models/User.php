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
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";  // Cambiar 'username' a 'email'
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);  // Cambiar de ':username' a ':email'
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($password === $user['password']) {
                return [
                    'email' => $user['email'],  // Cambiar de 'username' a 'email'
                    'isAdmin' => (bool)$user['isAdmin']
                ];
            }
        }

        return false;
    }


    public function createUser($email, $password, $name, $surname, $age, $isAdmin, $image, $permittedCourses)
    {
        // Consulta para insertar el nuevo usuario, incluyendo el campo email
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

        return $stmt->execute();
    }
}
