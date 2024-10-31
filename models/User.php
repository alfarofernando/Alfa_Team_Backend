<?php

class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function validateUser($username, $password)
    {
        // Consulta SQL para obtener el usuario por nombre
        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Comparar la contraseña directamente (sin hashear)
            if ($password === $user['password']) {
                return $user; // Devolver información del usuario si es válido
            }
        }

        return false; // Retornar false si el usuario no existe o la contraseña es incorrecta
    }
}
